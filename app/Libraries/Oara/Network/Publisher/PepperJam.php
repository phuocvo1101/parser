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
 * @category   Oara_Network_Publisher_PepperJam
 * @copyright  Fubra Limited
 * @version    Release: 01.00
 *
 */
class Oara_Network_Publisher_PepperJam extends Oara_Network {
	/**
	 * Export client.
	 * @var Oara_Curl_Access
	 */
	private $_client = null;

	/**
	 * Transaction Export Parameters
	 * @var array
	 */
	private $_exportTransactionParameters = null;

	/**
	 * Constructor and Login
	 * @param $traveljigsaw
	 * @return Oara_Network_Publisher_Tj_Export
	 */
	public function __construct($credentials) {
		$user = $credentials['user'];
		$password = $credentials['password'];

		$loginUrl = 'https://www.pepperjamnetwork.com/login.php';

		$valuesLogin = array(new Oara_Curl_Parameter('email', $user),
		new Oara_Curl_Parameter('passwd', $password),
		new Oara_Curl_Parameter('hideid', '')
		);

		$this->_client = new Oara_Curl_Access($loginUrl, $valuesLogin, $credentials);
	}
	/**
	 * Check the connection
	 */
	public function checkConnection() {
		$connection = false;
		$urls = array();
		$urls[] = new Oara_Curl_Request('http://www.pepperjamnetwork.com/affiliate/transactionrep.php', array());
		$exportReport = $this->_client->get($urls);

		if (preg_match("/\/logout\.php/", $exportReport[0], $matches)) {
			$connection = true;
		}
		return $connection;
	}
	/**
	 * (non-PHPdoc)
	 * @see library/Oara/Network/Oara_Network_Publisher_Base#getMerchantList()
	 */
	public function getMerchantList() {
		$merchants = Array();

		$urls = array();
		$urls[] = new Oara_Curl_Request('http://www.pepperjamnetwork.com/affiliate/program/manage?statuses[]=1&csv=1', array());
		$exportReport = $this->_client->get($urls);

		$merchantList = str_getcsv($exportReport[0], "\n");
		for ($i = 1; $i < count($merchantList); $i++){
			$merchant = str_getcsv($merchantList[$i], ",");
			$obj = Array();
			$obj['cid'] = $merchant[0];
			$obj['name'] = $merchant[1];
			$obj['url'] = $merchant[9];
			$merchants[] = $obj;
		}
		return $merchants;
	}
	/**
	 * (non-PHPdoc)
	 * @see library/Oara/Network/Oara_Network_Publisher_Base#getTransactionList($merchantId, $dStartDate, $dEndDate)
	 */
	public function getTransactionList($merchantList = null, Zend_Date $dStartDate = null, Zend_Date $dEndDate = null, $merchantMap = null) {
		$totalTransactions = Array();

		$valuesFormExport = array();
		$valuesFormExport[] = new Oara_Curl_Parameter('csv', 'csv');
		$valuesFormExport[] = new Oara_Curl_Parameter('ajax', 'ajax');
		$valuesFormExport[] = new Oara_Curl_Parameter('type', 'csv');
		$valuesFormExport[] = new Oara_Curl_Parameter('sortColumn', 'transid');
		$valuesFormExport[] = new Oara_Curl_Parameter('sortType', 'ASC');
		$valuesFormExport[] = new Oara_Curl_Parameter('startdate', $dStartDate->toString("yyyy-MM-dd"));
		$valuesFormExport[] = new Oara_Curl_Parameter('enddate', $dEndDate->toString("yyyy-MM-dd"));
		$valuesFormExport[] = new Oara_Curl_Parameter('programName', 'all');
		$valuesFormExport[] = new Oara_Curl_Parameter('website', '');
		$valuesFormExport[] = new Oara_Curl_Parameter('transactionType', '0');
		$valuesFormExport[] = new Oara_Curl_Parameter('creativeType', 'all');
		$valuesFormExport[] = new Oara_Curl_Parameter('advancedSubType', '');
		$valuesFormExport[] = new Oara_Curl_Parameter('saleIdSearch', '');

		$urls = array();
		$urls[] = new Oara_Curl_Request('http://www.pepperjamnetwork.com/affiliate/report_transaction_detail.php?', $valuesFormExport);
		$exportReport = $this->_client->get($urls);

		$exportData = str_getcsv($exportReport[0], "\n");

		$num = count($exportData);
		for ($i = 1; $i < $num; $i++) {
			$transactionExportArray = str_getcsv($exportData[$i], ",");
			if (in_array((int) $transactionExportArray[1], $merchantList)) {
				$transaction = Array();
				$merchantId = (int) $transactionExportArray[1];
				$transaction['merchantId'] = $merchantId;
				$transaction['date'] = $transactionExportArray[9];
				$transaction['unique_id'] = $transactionExportArray[0];

				if ($transactionExportArray[4] != null) {
					$transaction['custom_id'] = $transactionExportArray[4];
				}

				if ($transactionExportArray[10] == 'Pending' || $transactionExportArray[10] == 'Delayed' || $transactionExportArray[10] == 'Updated Pending Commission') {
					$transaction['status'] = Oara_Utilities::STATUS_PENDING;
				} else
				if ($transactionExportArray[10] == 'Locked') {
					$transaction['status'] = Oara_Utilities::STATUS_DECLINED;
				} else
				if ($transactionExportArray[10] == 'Paid') {
					$transaction['status'] = Oara_Utilities::STATUS_CONFIRMED;
				} else {
					throw new Exception("Status {$transactionExportArray[10]} unknown");
				}

				if (preg_match("/[-+]?[0-9]*\.?[0-9]+/", $transactionExportArray[7], $match)){
					$transaction['amount'] = (double)$match[0];
				}
				if (preg_match("/[-+]?[0-9]*\.?[0-9]+/", $transactionExportArray[8], $match)){
					$transaction['commission'] = (double)$match[0];
				}
				$totalTransactions[] = $transaction;
			}
		}

		return $totalTransactions;

	}

	/**
	 * (non-PHPdoc)
	 * @see Oara/Network/Oara_Network_Publisher_Base#getPaymentHistory()
	 */
	public function getPaymentHistory() {
		$paymentHistory = array();


		$pointer = new Zend_Date("2010-01-01", "yyyy-MM-dd");
		$now = new Zend_Date();
		while ($now->getYear() >= $pointer->getYear()){
			$valuesFormExport = array();
			$valuesFormExport[] = new Oara_Curl_Parameter('csv', 'csv');
			$valuesFormExport[] = new Oara_Curl_Parameter('ajax', 'ajax');
			$valuesFormExport[] = new Oara_Curl_Parameter('type', 'csv');
			$valuesFormExport[] = new Oara_Curl_Parameter('sortColumn', 'paymentid');
			$valuesFormExport[] = new Oara_Curl_Parameter('sortType', 'ASC');
			$valuesFormExport[] = new Oara_Curl_Parameter('startdate', $pointer->toString("yyyy")."-01-01");
			$valuesFormExport[] = new Oara_Curl_Parameter('enddate',  $pointer->toString("yyyy")."-12-31");
			$valuesFormExport[] = new Oara_Curl_Parameter('payid_search', '');

			$urls = array();
			$urls[] = new Oara_Curl_Request('http://www.pepperjamnetwork.com/affiliate/report_payment_history.php?', $valuesFormExport);
			$exportReport = $this->_client->get($urls);

			$exportData = str_getcsv($exportReport[0], "\n");
			$num = count($exportData);
			for ($i = 1; $i < $num; $i++) {
				$paymentExportArray = str_getcsv($exportData[$i], ",");
				$obj = array();
				$obj['date'] = $paymentExportArray[5];
				$obj['pid'] = $paymentExportArray[0];
				if (preg_match("/[-+]?[0-9]*\.?[0-9]+/", $paymentExportArray[4], $match)){
					$obj['value'] = (double)$match[0];
				}
				$obj['method'] = $paymentExportArray[2];
				$paymentHistory[] = $obj;
			}
			$pointer->addYear(1);
		}

		return $paymentHistory;
	}
	/**
	 *
	 * It returns the transactions for a payment
	 * @param int $paymentId
	 */
	public function paymentTransactions($paymentId, $merchantList, $startDate) {
		$transactionList = array();
		
		$valuesFormExport = array();
		$valuesFormExport[] = new Oara_Curl_Parameter('csv', 'csv');
		$valuesFormExport[] = new Oara_Curl_Parameter('ajax', 'ajax');
		$valuesFormExport[] = new Oara_Curl_Parameter('type', 'csv');
		$valuesFormExport[] = new Oara_Curl_Parameter('sortColumn', '');
		$valuesFormExport[] = new Oara_Curl_Parameter('sortType', '');
		$valuesFormExport[] = new Oara_Curl_Parameter('startdate', '');
		$valuesFormExport[] = new Oara_Curl_Parameter('enddate', '');
		$valuesFormExport[] = new Oara_Curl_Parameter('paymentid', $paymentId);

		$urls = array();
		$urls[] = new Oara_Curl_Request('http://www.pepperjamnetwork.com/affiliate/report_payment_history_detail.php?', $valuesFormExport);
		$exportReport = $this->_client->get($urls);

		$exportData = str_getcsv($exportReport[0], "\n");
		$num = count($exportData);
		for ($i = 1; $i < $num; $i++) {
			$transactionArray = str_getcsv($exportData[$i], ",");
			$transactionList[] = $transactionArray[1];
		}
		
		return $transactionList;
	}

}
