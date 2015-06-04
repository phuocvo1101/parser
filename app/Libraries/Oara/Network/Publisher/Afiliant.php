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
 * @author     Carlos Morillo Merino
 * @category   Oara_Network_Publisher_Afiliant
 * @copyright  Fubra Limited
 * @version    Release: 01.00
 *
 */
class Oara_Network_Publisher_Afiliant extends Oara_Network {


	/**
	 * Client
	 * @var unknown_type
	 */
	private $_client = null;
	/**
	 * Constructor and Login
	 * @param $buy
	 * @return Oara_Network_Publisher_Buy_Api
	 */
	public function __construct($credentials) {

		$user = $credentials['user'];
		$password = $credentials['password'];

		$loginUrl = 'https://ssl.afiliant.com/publisher/index.php?a=auth';


		$valuesLogin = array(new Oara_Curl_Parameter('login', $user),
		new Oara_Curl_Parameter('password', $password),
		new Oara_Curl_Parameter('submit', "")
		);

		$this->_client = new Oara_Curl_Access($loginUrl, $valuesLogin, $credentials);

	}
	/**
	 * Check the connection
	 */
	public function checkConnection() {
		$connection = false;
		$urls = array();
		$urls[] = new Oara_Curl_Request('http://www.afiliant.com/publisher/index.php', array());
		$exportReport = $this->_client->get($urls);
		if (!preg_match("/index.php?a=logout/", $exportReport[0], $matches)) {
			$connection = true;
		}
		return $connection;
	}

	/**
	 * (non-PHPdoc)
	 * @see library/Oara/Network/Oara_Network_Publisher_Interface#getMerchantList()
	 */
	public function getMerchantList() {
		$merchants = Array();

		$valuesFromExport = array(
		new Oara_Curl_Parameter('c', 'stats'),
		new Oara_Curl_Parameter('a', 'listMonth')
		);
		$urls = array();
		$urls[] = new Oara_Curl_Request('http://www.afiliant.com/publisher/index.php?', $valuesFromExport);
		$exportReport = $this->_client->get($urls);

		$dom = new Zend_Dom_Query($exportReport[0]);
		$results = $dom->query('#id_shop');
		$merchantLines = $results->current()->childNodes;
		for ($i = 0; $i < $merchantLines->length; $i++) {
			$cid = $merchantLines->item($i)->attributes->getNamedItem("value")->nodeValue;
			if (is_numeric($cid)) {
				$obj = array();
				$name = $merchantLines->item($i)->nodeValue;
				$obj = array();
				$obj['cid'] = $cid;
				$obj['name'] = $name;
				$merchants[] = $obj;
			}
		}

		return $merchants;
	}

	/**
	 * (non-PHPdoc)
	 * @see library/Oara/Network/Oara_Network_Publisher_Interface#getTransactionList($aMerchantIds, $dStartDate, $dEndDate)
	 */
	public function getTransactionList($merchantList = null, Zend_Date $dStartDate = null, Zend_Date $dEndDate = null, $merchantMap = null) {
		$totalTransactions = array();

		$valuesFromExport = array();
		$valuesFromExport[] = new Oara_Curl_Parameter('c', 'stats');
		$valuesFromExport[] = new Oara_Curl_Parameter('id_shop', '');
		$valuesFromExport[] = new Oara_Curl_Parameter('a', 'listMonthDayOrder');
		$valuesFromExport[] = new Oara_Curl_Parameter('month', $dEndDate->get(Zend_Date::YEAR)."-".$dEndDate->get(Zend_Date::MONTH));
		$valuesFromExport[] = new Oara_Curl_Parameter('export', 'csv');

		$urls = array();
		$urls[] = new Oara_Curl_Request('http://www.afiliant.com/publisher/index.php?', $valuesFromExport);

		$exportData = null;
		try{
			$exportReport = $this->_client->get($urls);
			$exportData = str_getcsv($exportReport[0], "\r\n");
		} catch(Exception $e){
			echo "No data";
		}
		if ($exportData != null){
			$num = count($exportData);
			for ($i = 0; $i < $num; $i++) {
				$transactionExportArray = str_getcsv($exportData[$i], ";");
				
				if (isset($merchantMap[$transactionExportArray[1]])) {
					$transaction = Array();
					$merchantId = (int) $merchantMap[$transactionExportArray[1]];
					$transaction['merchantId'] = $merchantId;
					$transactionDate = new Zend_Date($transactionExportArray[0], 'yyyy-MM-dd');
					$transaction['date'] = $transactionDate->toString("yyyy-MM-dd 00:00:00");
					$transaction['unique_id'] = $transactionExportArray[3];

					if (isset($transactionExportArray[8]) && $transactionExportArray[8] != null) {
						$transaction['custom_id'] = $transactionExportArray[8];
					}

					if ($transactionExportArray[6] == 'zaakceptowana') {
						$transaction['status'] = Oara_Utilities::STATUS_CONFIRMED;
					} else
					if ($transactionExportArray[6] == 'oczekuje') {
						$transaction['status'] = Oara_Utilities::STATUS_PENDING;
					} else
					if ($transactionExportArray[6] == 'odrzucona') {
						$transaction['status'] = Oara_Utilities::STATUS_DECLINED;
					}
					$transaction['amount'] = Oara_Utilities::parseDouble($transactionExportArray[4]);
					$transaction['commission'] = Oara_Utilities::parseDouble($transactionExportArray[5]);
					$totalTransactions[] = $transaction;
				}
			}
		}

		return $totalTransactions;
	}

	/**

	/**
	 * (non-PHPdoc)
	 * @see Oara/Network/Oara_Network_Publisher_Base#getPaymentHistory()
	 */
	public function getPaymentHistory() {
		$paymentHistory = array();

		return $paymentHistory;
	}

	/**
	 *
	 * It returns the transactions for a payment
	 * @param int $paymentId
	 */
	public function paymentTransactions($paymentId, $merchantList, $startDate) {
		$transactionList = array();


		return $transactionList;
	}
}
