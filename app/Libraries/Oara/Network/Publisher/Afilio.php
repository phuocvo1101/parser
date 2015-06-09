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
 * API Class
 *
 * @author Carlos Morillo Merino
 * @category Oara_Network_Publisher_Afiliant
 * @copyright Fubra Limited
 * @version Release: 01.00
 *         
 */
class Oara_Network_Publisher_Afilio extends Oara_Network {
	
	/**
	 * Client
	 *
	 * @var unknown_type
	 */
	private $_client = null;
	
	/**
	 * Constructor and Login
	 *
	 * @param $buy
	 * @return Oara_Network_Publisher_Buy_Api
	 */
	public function __construct($credentials) {
		$user = $credentials ['user'];
		$password = $credentials ['password'];
		
		$loginUrl = 'http://v2.afilio.com.br/index.php';
		
		$valuesLogin = array (
				new Oara_Curl_Parameter ( 'auth_login', $user ),
				new Oara_Curl_Parameter ( 'auth_pass', $password ),
				new Oara_Curl_Parameter ( 'auth_type', "aff" ),
				new Oara_Curl_Parameter ( 'Ok', "ok" ),
				new Oara_Curl_Parameter ( 'from', "afilio" ),
				new Oara_Curl_Parameter ( 'url_error', "http://www.afilio.com.br/login-incorreto" ),
				new Oara_Curl_Parameter ( 'id_regie', "3" ) 
		);
		
		$this->_client = new Oara_Curl_Access ( $loginUrl, $valuesLogin, $credentials );
	}
	/**
	 * Check the connection
	 */
	public function checkConnection() {
		$connection = false;
		$urls = array ();
		$urls [] = new Oara_Curl_Request ( 'http://v2.afilio.com.br/aff/', array () );
		$exportReport = $this->_client->get ( $urls );
		if (preg_match ( "/logout/", $exportReport [0], $matches )) {
			$connection = true;
		}
		return $connection;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see library/Oara/Network/Oara_Network_Publisher_Interface#getMerchantList()
	 */
	public function getMerchantList() {
		$merchants = Array ();
		
		$valuesFromExport = array ();
		$urls = array ();
		$urls [] = new Oara_Curl_Request ( 'http://v2.afilio.com.br/aff/aff_manage_sale.php', $valuesFromExport );
		$exportReport = $this->_client->get ( $urls );
		
		$dom = new Zend_Dom_Query ( $exportReport [0] );
		$results = $dom->query ( '#p_nProgId' );
		$merchantLines = $results->current ()->childNodes;
		for($i = 0; $i < $merchantLines->length; $i ++) {
			$cid = $merchantLines->item ( $i )->attributes->getNamedItem ( "value" )->nodeValue;
			if (is_numeric ( $cid )) {
				$obj = array ();
				$name = $merchantLines->item ( $i )->nodeValue;
				$obj = array ();
				$obj ['cid'] = $cid;
				$obj ['name'] = $name;
				$merchants [] = $obj;
			}
		}
		
		return $merchants;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see library/Oara/Network/Oara_Network_Publisher_Interface#getTransactionList($aMerchantIds, $dStartDate, $dEndDate)
	 */
	public function getTransactionList($merchantList = null, Zend_Date $dStartDate = null, Zend_Date $dEndDate = null, $merchantMap = null) {
		$totalTransactions = array ();
		
		$valuesFromExport = array ();
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'getExcel', '1' );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'p_sSearchMode', 'custom' );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'p_nType', 'sale' );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'p_sPeriod', 'day' );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'export', 'csv' );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'p_nStatus', '3' );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'p_nNbRowsByPage', '50' );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'p_nProgId', '' );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'p_sStartDate', $dStartDate->toString ( "dd/MM/yyyy" ) );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'p_sEndDate', $dEndDate->toString ( "dd/MM/yyyy" ) );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'p_nPage', '1' );
		
		$urls = array ();
		$urls [] = new Oara_Curl_Request ( 'http://v2.afilio.com.br/include/lib/aff_lib_manage_sale.php?', $valuesFromExport );
		
		$exportReport = $this->_client->get ( $urls );
		$dom = new Zend_Dom_Query ( $exportReport [0] );
		
		$tableList = $dom->query ( 'table' );
		$exportData = self::htmlToCsv ( self::DOMinnerHTML ( $tableList->next () ) );
		
		$num = count ( $exportData );
		for($i = 0; $i < $num; $i ++) {
			$transactionExportArray = explode (";,", $exportData [$i]);
			if (isset ( $merchantMap [$transactionExportArray [0]] ) && in_array($merchantMap [$transactionExportArray [0]], $merchantList)) {
				
				$transaction = Array ();
				$transaction ['merchantId'] = $merchantMap [$transactionExportArray [0]];
				$transaction ['unique_id'] = $transactionExportArray [4];
				$transactionDate = new Zend_Date ( $transactionExportArray [1], 'dd/MM/yy HH:mm:dd', 'en' );
				$transaction ['date'] = $transactionDate->toString ( "yyyy-MM-dd HH:mm:ss" );
				
				$transaction ['customId'] = $transactionExportArray [5];
				
				
				if ($transactionExportArray [7] == "Accepted" || $transactionExportArray [7] == "Accepté") {
					$transaction ['status'] = Oara_Utilities::STATUS_CONFIRMED;
				} else if ($transactionExportArray [7] == "Pending" || $transactionExportArray [7] == "En attente") {
					$transaction ['status'] = Oara_Utilities::STATUS_PENDING;
				} else if ($transactionExportArray [7] == "Rejected" || $transactionExportArray [7] == "Refusé" || $transactionExportArray [7] == "Refused") {
					$transaction ['status'] = Oara_Utilities::STATUS_DECLINED;
				} else {
					throw new Exception ( "New status found {$transactionExportArray [7]}" );
				}
				
				$transaction ['amount'] = Oara_Utilities::parseDouble ( preg_replace ( "/[^0-9\.,]/", "", $transactionExportArray [6] ) );
				$transaction ['commission'] = Oara_Utilities::parseDouble ( preg_replace ( "/[^0-9\.,]/", "", $transactionExportArray [6] ) );
				
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
	
	/**
	 *
	 *
	 *
	 * It returns the transactions for a payment
	 *
	 * @param int $paymentId        	
	 */
	public function paymentTransactions($paymentId, $merchantList, $startDate) {
		$transactionList = array ();
		
		return $transactionList;
	}
	
	/**
	 *
	 *
	 * Function that Convert from a table to Csv
	 * 
	 * @param unknown_type $html        	
	 */
	private function htmlToCsv($html) {
		$html = str_replace ( array (
				"\t",
				"\r",
				"\n" 
		), "", $html );
		$csv = "";
		$dom = new Zend_Dom_Query ( $html );
		$results = $dom->query ( 'tr' );
		$count = count ( $results ); // get number of matches: 4
		foreach ( $results as $result ) {
			
			$domTd = new Zend_Dom_Query ( self::DOMinnerHTML($result) );
			$resultsTd = $domTd->query ( 'td' );
			$countTd = count ( $resultsTd );
			$i = 0;
			foreach( $resultsTd as $resultTd) {
				$value = $resultTd->nodeValue;
				if ($i != $countTd - 1) {
					$csv .= trim ( $value ) . ";,";
				} else {
					$csv .= trim ( $value );
				}
				$i++;
			}
			$csv .= "\n";
		}
		$exportData = str_getcsv ( $csv, "\n" );
		return $exportData;
	}
	/**
	 *
	 *
	 * Function that returns the innet HTML code
	 * 
	 * @param unknown_type $element        	
	 */
	private function DOMinnerHTML($element) {
		$innerHTML = "";
		$children = $element->childNodes;
		foreach ( $children as $child ) {
			$tmp_dom = new DOMDocument ();
			$tmp_dom->appendChild ( $tmp_dom->importNode ( $child, true ) );
			$innerHTML .= trim ( $tmp_dom->saveHTML () );
		}
		return $innerHTML;
	}
}
