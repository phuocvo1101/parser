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
 * @category   Oara_Network_Publisher_Affiliate4You
 * @copyright  Fubra Limited
 * @version    Release: 01.00
 *
 */
class Oara_Network_Publisher_Affiliate4You extends Oara_Network {

	private $_user = null;
	private $_pass = null;

	/**
	 * Client
	 * @var unknown_type
	 */
	private $_client = null;
	/**
	 * Constructor and Login
	 * @param $af
	 * @return Oara_Network_Publisher_Af_Export
	 */
	public function __construct($credentials) {
		$this->_user = $credentials['user'];
		$this->_pass = $credentials['apiPassword'];


		$this->_client = new Oara_Curl_Access("http://www.affiliate4you.nl/", array(), $credentials);
	}
	/**
	 * Check the connection
	 */
	public function checkConnection() {
		//If not login properly the construct launch an exception
		$connection = true;
		$urls = array();
		$valuesFromExport = array();
		$valuesFromExport[] = new Oara_Curl_Parameter('email', $this->_user);
		$valuesFromExport[] = new Oara_Curl_Parameter('apikey', $this->_pass);
		$valuesFromExport[] = new Oara_Curl_Parameter('limit', "1");
		$urls[] = new Oara_Curl_Request("http://api.affiliate4you.nl/1.0/campagnes/all.csv?", $valuesFromExport);

		try{
			$result = $this->_client->get($urls);
		} catch (Exception $e){
			$connection = false;
		}
		return $connection;
	}
	/**
	 * (non-PHPdoc)
	 * @see library/Oara/Network/Oara_Network_Publisher_Interface#getMerchantList()
	 */
	public function getMerchantList() {
		$merchants = Array();

		$page = 1;
		$import = true;
		while ($import){

			$totalRows = ($page*100);

			$urls = array();
			$valuesFromExport = array();
			$valuesFromExport[] = new Oara_Curl_Parameter('email', $this->_user);
			$valuesFromExport[] = new Oara_Curl_Parameter('apikey', $this->_pass);
			$valuesFromExport[] = new Oara_Curl_Parameter('limit', 100);
			$valuesFromExport[] = new Oara_Curl_Parameter('page', $page);
			$urls[] = new Oara_Curl_Request("http://api.affiliate4you.nl/1.0/campagnes/all.csv?", $valuesFromExport);
			$result = $this->_client->get($urls);
			$exportData = str_getcsv($result[0], "\n");

			for ($i = 1; $i < count($exportData); $i++){
				$merchantExportArray = str_getcsv($exportData[$i], ";");
				$obj = Array();
				$obj['cid'] = $merchantExportArray[1];
				$obj['name'] = $merchantExportArray[2];
				$merchants[] = $obj;
			}

			if (count($exportData) != ($totalRows + 1)){
				$import = false;
			}
			$page++;

		}





		return $merchants;
	}

	/**
	 * (non-PHPdoc)
	 * @see library/Oara/Network/Oara_Network_Publisher_Interface#getTransactionList($aMerchantIds, $dStartDate, $dEndDate, $sTransactionStatus)
	 */
	public function getTransactionList($merchantList = null, Zend_Date $dStartDate = null, Zend_Date $dEndDate = null, $merchantMap = null) {
		$transactions = array();
		$page = 1;
		$import = true;

		while ($import){

			$totalRows = ($page*300);

			$urls = array();
			$valuesFromExport = array();
			$valuesFromExport[] = new Oara_Curl_Parameter('email', $this->_user);
			$valuesFromExport[] = new Oara_Curl_Parameter('apikey', $this->_pass);
			$valuesFromExport[] = new Oara_Curl_Parameter('from', $dStartDate->toString("yyyy-MM-dd"));
			$valuesFromExport[] = new Oara_Curl_Parameter('to', $dEndDate->toString("yyyy-MM-dd"));
			$valuesFromExport[] = new Oara_Curl_Parameter('limit', 300);
			$valuesFromExport[] = new Oara_Curl_Parameter('page', $page);
			$urls[] = new Oara_Curl_Request("http://api.affiliate4you.nl/1.0/orders.csv?", $valuesFromExport);
			try{
				$result = $this->_client->get($urls);
			} catch (Exception $e){
				return $transactions;
			}

			$exportData = str_getcsv($result[0], "\n");

			for ($i = 1; $i < count($exportData); $i++){

				$transactionExportArray = str_getcsv($exportData[$i], ";");
				if (in_array($transactionExportArray[12], $merchantList)){
					$transaction = Array();
					$transaction['unique_id'] = $transactionExportArray[3];
					$transaction['merchantId'] = $transactionExportArray[12];
					$transactionDate = new Zend_Date($transactionExportArray[0], 'yyyy-MM-dd HH:mm:ss');
					$transaction['date'] = $transactionDate->toString("yyyy-MM-dd HH:mm:ss");

					if ($transactionExportArray[8] != null) {
						$transaction['custom_id'] = $transactionExportArray[8];
					}

					if ($transactionExportArray[5] == 'approved') {
						$transaction['status'] = Oara_Utilities::STATUS_CONFIRMED;
					} else
					if ($transactionExportArray[5] == 'new' || $transactionExportArray[5] == 'onhold') {
						$transaction['status'] = Oara_Utilities::STATUS_PENDING;
					} else
					if ($transactionExportArray[5] == 'declined') {
						$transaction['status'] = Oara_Utilities::STATUS_DECLINED;
					}

					$transaction['amount'] = $transactionExportArray[4];

					$transaction['commission'] = $transactionExportArray[1];
					$transactions[] = $transaction;
				}
			}


			if (count($exportData) != ($totalRows + 1)){
				$import = false;
			}
			$page++;

		}



		return $transactions;
	}


	/**
	 * (non-PHPdoc)
	 * @see Oara/Network/Oara_Network_Publisher_Base#getPaymentHistory()
	 */
	public function getPaymentHistory() {
		$paymentHistory = array();
		return $paymentHistory;
	}

}
