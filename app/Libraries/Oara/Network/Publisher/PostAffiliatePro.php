<?php
/**
 The goal of the Open Affiliate Report Aggregator (OARA) is to develop a set
 of PHP classes that can download affiliate reports from a number of affiliate networks, and store the data in a common format.

 Copyright (C) 2014  Fubra Limited
 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or any later version.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.
 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.

 Contact
 ------------
 Fubra Limited <support@fubra.com> , +44 (0)1252 367 200
 **/
/**
 * Export Class
 *
 * @author     Carlos Morillo Merino
 * @category   Oara_Network_Publisher_PureVPN
 * @copyright  Fubra Limited
 * @version    Release: 01.00
 *
 */
class Oara_Network_Publisher_PostAffiliatePro extends Oara_Network {
	private $_credentials = null;
	/**
	 * Client
	 * 
	 * @var unknown_type
	 */
	private $_client = null;
	
	/**
	 * Security Code
	 * 
	 * @var unknown_type
	 */
	private $_s = null;
	
	/**
	 * Login Result
	 * 
	 * @var unknown_type
	 */
	private $_loginResult = null;
	
	/**
	 * Transaction List
	 * 
	 * @var unknown_type
	 */
	private $_transactionList = null;
	/**
	 * Constructor and Login
	 * 
	 * @param
	 *        	$credentials
	 * @return Oara_Network_Publisher_PureVPN
	 */
	public function __construct($credentials) {
		$this->_credentials = $credentials;
		self::logIn ();
	}
	private function logIn() {
		
		$html = file_get_contents ( "http://".$this->_credentials["domain"].'/affiliates/login.php#login' );
		if (preg_match ( "/\"S\\\\\",\\\\\"(.*?)\\\\\"/", $html, $matches )) {
			$this->_s = $matches [1];
		}
		
		$valuesLogin = array (
				new Oara_Curl_Parameter ( 'D', '{"C":"Gpf_Rpc_Server", "M":"run", "requests":[{"C":"Gpf_Auth_Service", "M":"authenticate", "fields":[["name","value"],["Id",""],["username","' . $this->_credentials ["user"] . '"],["password","' . $this->_credentials ["password"] . '"],["rememberMe","Y"],["language","en-US"]]}], "S":"' . $this->_s . '"}' ) 
		);
		
		$loginUrl = "http://".$this->_credentials["domain"].'/scripts/server.php?';
		$this->_client = new Oara_Curl_Access ( $loginUrl, $valuesLogin, $this->_credentials );
		$this->_loginResult = $this->_client->getConstructResult ();
	}
	/**
	 * Check the connection
	 */
	public function checkConnection() {
		// If not login properly the construct launch an exception
		$connection = true;
		
		if (! preg_match ( "/\"success\":\"Y\"/", $this->_loginResult )) {
			$connection = false;
		}
		
		return $connection;
	}
	/**
	 * (non-PHPdoc)
	 * 
	 * @see library/Oara/Network/Oara_Network_Publisher_Interface#getMerchantList()
	 */
	public function getMerchantList() {
		$merchants = array ();
		
		$obj = array ();
		$obj ['cid'] = "1";
		$obj ['name'] = "Post Affiliate Pro ({$this->_credentials["domain"]})";
		$merchants [] = $obj;
		
		return $merchants;
	}
	
	/**
	 * (non-PHPdoc)
	 * 
	 * @see library/Oara/Network/Oara_Network_Publisher_Interface#getTransactionList($aMerchantIds, $dStartDate, $dEndDate, $sTransactionStatus)
	 */
	public function getTransactionList($merchantList = null, Zend_Date $dStartDate = null, Zend_Date $dEndDate = null, $merchantMap = null) {
		$totalTransactions = array ();
		$valuesFormExport = array ();
		
		if ($this->_transactionList == null) {
			$urls = array ();
			$valuesExport = array (
					new Oara_Curl_Parameter ( 'D', '{"C":"Pap_Affiliates_Reports_TransactionsGrid", "M":"getCSVFile", "S":"' . $this->_s . '", "FormResponse":"Y", "sort_col":"dateinserted", "sort_asc":false, "offset":0, "limit":30, "columns":[["id"],["id"],["commission"],["totalcost"],["fixedcost"],["t_orderid"],["productid"],["dateinserted"],["name"],["rtype"],["tier"],["commissionTypeName"],["rstatus"],["merchantnote"],["channel"]]}' ) 
			);
			$urls [] = new Oara_Curl_Request ( "http://".$this->_credentials["domain"].'/scripts/server.php?', $valuesExport );
			$exportReport = array ();
			$exportReport = $this->_client->post ( $urls );
			$this->_transactionList = str_getcsv ( $exportReport [0], "\n" );
		}
		$exportData = $this->_transactionList;
		
		$num = count ( $exportData );
		for($i = 1; $i < $num; $i ++) {
			
			$transactionExportArray = str_getcsv ( $exportData [$i], "," );
			// print_r($transactionExportArray);
			
			$transaction = Array ();
			$transaction ['merchantId'] = 1;
			$transaction ['uniqueId'] = $transactionExportArray [3];
			$transactionDate = new Zend_Date ( $transactionExportArray [5], 'yyyy-MM-dd HH:mm:ss', 'en' );
			$transaction ['date'] = $transactionDate->toString ( "yyyy-MM-dd HH:mm:ss" );
			unset ( $transactionDate );
			
			
			if ($transactionExportArray [10] == 'D'){
				$transaction ['status'] = Oara_Utilities::STATUS_DECLINED;
			} else if ($transactionExportArray [10] == 'P'){
				$transaction ['status'] = Oara_Utilities::STATUS_PENDING;
			} else if ($transactionExportArray [10] == 'A'){
				$transaction ['status'] = Oara_Utilities::STATUS_CONFIRMED;
			}
			
			$transaction ['amount'] = Oara_Utilities::parseDouble ( $transactionExportArray [1] );
			$transaction ['commission'] = Oara_Utilities::parseDouble ( $transactionExportArray [0] );
			
			if ($transaction ['date'] >= $dStartDate->toString ( "yyyy-MM-dd HH:mm:ss" ) && $transaction ['date'] <= $dEndDate->toString ( "yyyy-MM-dd HH:mm:ss" )) {
				$totalTransactions [] = $transaction;
			}
		}
		
		return $totalTransactions;
	}
	
	/**
	 * (non-PHPdoc)
	 * 
	 * @see Oara/Network/Oara_Network_Publisher_Base#getPaymentHistory()
	 */
	public function getPaymentHistory() {
		$paymentHistory = array ();
		
		return $paymentHistory;
	}
}
