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
 * Api Class
 *
 * @author     Carlos Morillo Merino
 * @category   Oara_Network_Publisher_Zn
 * @copyright  Fubra Limited
 * @version    Release: 01.00
 *
 */

require "Zanox/Zapi/ApiClient.php";

class Oara_Network_Publisher_Zanox extends Oara_Network {
	/**
	 * Soap client.
	 */
	private $_apiClient = null;

	/**
	 * page Size.
	 */
	private $_pageSize = 50;

	/**
	 * Constructor.
	 * @param $affiliateWindow
	 * @return Oara_Network_Publisher_Zn_Api
	 */
	public function __construct($credentials) {

		$api = ApiClient::factory(PROTOCOL_SOAP, VERSION_2011_03_01);

		$connectId = $credentials['connectId'];
		$secretKey = $credentials['secretKey'];
		//$publicKey = $credentials['publicKey'];

		$api->setConnectId($connectId);
		$api->setSecretKey($secretKey);
		//$api->setPublicKey($publicKey);

		$this->_apiClient = $api;

	}
	/**
	 * Check the connection
	 */
	public function checkConnection() {
		$connection = true;
		try {
			$profile = $this->_apiClient->getProfile();
		} catch (Exception $e) {
			$connection = false;
		}

		return $connection;
	}
	/**
	 * (non-PHPdoc)
	 * @see library/Oara/Network/Oara_Network_Publisher_Base#getMerchantList()
	 */
	public function getMerchantList() {
		$merchantList = array();
	
		$programApplicationList = $this->_apiClient->getProgramApplications(null, null, "confirmed", 0, $this->_pageSize);
		if ($programApplicationList->total > 0) {
			$iterationProgramApplicationList = self::calculeIterationNumber($programApplicationList->total, $this->_pageSize);
			for ($j = 0; $j < $iterationProgramApplicationList; $j++) {

				$programApplicationList = $this->_apiClient->getProgramApplications(null, null, "confirmed", $j, $this->_pageSize);
				foreach ($programApplicationList->programApplicationItems->programApplicationItem as $programApplication) {
					if (!isset($merchantList[$programApplication->program->id])) {
						$obj = array();
						$obj['cid'] = $programApplication->program->id;
						$obj['name'] = $programApplication->program->_;
						$merchantList[$programApplication->program->id] = $obj;
					}
				}

			}
		}
		return $merchantList;
	}
	/**
	 * (non-PHPdoc)
	 * @see library/Oara/Network/Oara_Network_Publisher_Base#getTransactionList($merchantId,$dStartDate,$dEndDate)
	 */
	public function getTransactionList($merchantList = null, Zend_Date $dStartDate = null, Zend_Date $dEndDate = null, $merchantMap = null) {
		$totalTransactions = array();

		$dStartDate = clone $dStartDate;
		$dStartDate->setHour("00");
		$dStartDate->setMinute("00");
		$dStartDate->setSecond("00");
		$dEndDate = clone $dEndDate;
		$dEndDate->setHour("23");
		$dEndDate->setMinute("59");
		$dEndDate->setSecond("59");

		$dateArray = Oara_Utilities::daysOfDifference($dStartDate, $dEndDate);
		foreach ($dateArray as $date) {
			$totalAuxTransactions = array();
			$transactionList = $this->getSales($date->toString("yyyy-MM-dd"), 0, $this->_pageSize);
			
			if ($transactionList->total > 0) {
				$iteration = self::calculeIterationNumber($transactionList->total, $this->_pageSize);
				$totalAuxTransactions = array_merge($totalAuxTransactions, $transactionList->saleItems->saleItem);
				for ($i = 1; $i < $iteration; $i++) {
					$transactionList = $this->getSales($date->toString("yyyy-MM-dd"), $i, $this->_pageSize);
					$totalAuxTransactions = array_merge($totalAuxTransactions, $transactionList->saleItems->saleItem);
					unset($transactionList);
					gc_collect_cycles();
				}

			}
			$leadList = $this->_apiClient->getLeads($date->toString("yyyy-MM-dd"), 'trackingDate', null, null, null, 0, $this->_pageSize);
			if ($leadList->total > 0) {
				$iteration = self::calculeIterationNumber($leadList->total, $this->_pageSize);
				$totalAuxTransactions = array_merge($totalAuxTransactions, $leadList->leadItems->leadItem );
				for ($i = 1; $i < $iteration; $i++) {
					$leadList = $this->_apiClient->getLeads($date->toString("yyyy-MM-dd"), 'trackingDate', null, null, null, $i, $this->_pageSize);
					$totalAuxTransactions = array_merge($totalAuxTransactions, $leadList->leadItems->leadItem );
					unset($leadList);
					gc_collect_cycles();
				}
			}

			foreach ($totalAuxTransactions as $transaction) {

				if (in_array($transaction->program->id, $merchantList)) {
					$obj = array();
					
					$obj['currency'] = $transaction->currency;
					
					if ($transaction->reviewState == 'confirmed') {
						$obj['status'] = Oara_Utilities::STATUS_CONFIRMED;
					} else
						if ($transaction->reviewState == 'open' || $transaction->reviewState == 'approved') {
							$obj['status'] = Oara_Utilities::STATUS_PENDING;
						} else
							if ($transaction->reviewState == 'rejected') {
								$obj['status'] = Oara_Utilities::STATUS_DECLINED;
							}
					if (!isset($transaction->amount) || $transaction->amount == 0) {
						$obj['amount'] = $transaction->commission;
					} else {
						$obj['amount'] = $transaction->amount;
					}

					if (isset($transaction->gpps) && $transaction->gpps != null) {
						foreach ($transaction->gpps->gpp as $gpp) {
							if ($gpp->id == "zpar0") {
								if (strlen($gpp->_) > 100) {
									$gpp->_ = substr($gpp->_, 0, 100);
								}
								$obj['custom_id'] = $gpp->_;
							}
						}
					}
					$obj['unique_id'] = $transaction->id;
					$obj['commission'] = $transaction->commission;
					$transactionDate = new Zend_Date($transaction->trackingDate, "yyyy-MM-dd HH:mm:ss");
					$obj['date'] = $transactionDate->toString("yyyy-MM-dd HH:mm:ss");
					$obj['merchantId'] = $transaction->program->id;
					$totalTransactions[] = $obj;
				}

			}
			unset($totalAuxTransactions);
			gc_collect_cycles();
		}
		return $totalTransactions;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Oara/Network/Oara_Network_Publisher_Base#getPaymentHistory()
	 */

	public function getPaymentHistory() {
		$paymentHistory = array();
		/*
		$paymentList = $this->_apiClient->getPayments(0, $this->_pageSize);

		if ($paymentList->total > 0) {
			$iteration = self::calculeIterationNumber($paymentList->total, $this->_pageSize);
			for ($j = 0; $j < $iteration; $j++) {
				$paymentList = $this->_apiClient->getPayments($j, $this->_pageSize);
				foreach ($paymentList->paymentItems->paymentItem as $payment) {
					$obj = array();
					$paymentDate = new Zend_Date($payment->createDate, "yyyy-MM-ddTHH:mm:ss");
					$obj['method'] = 'BACS';
					$obj['pid'] = $paymentDate->toString("yyyyMMddHHmmss");
					$obj['value'] = $payment->amount;

					$obj['date'] = $paymentDate->toString("yyyy-MM-dd HH:mm:ss");

					$paymentHistory[] = $obj;
				}
			}
		}
		*/
		return $paymentHistory;
	}
	
	private function getSales($date, $page, $pageSize, $iteration = 0){
		$transactionList = array();
		try{
			$transactionList = $this->_apiClient->getSales($date, 'trackingDate', null, null, null, $page, $pageSize, $iteration);
		} catch (Exception $e){
			$iteration++;
			if ($iteration < 5){
				$transactionList = self::getSales($date, $page, $pageSize, $iteration);
			}
			
		}
		return $transactionList;
		
	}

	/**
	 * Calculate the number of iterations needed
	 * @param $rowAvailable
	 * @param $rowsReturned
	 */
	private function calculeIterationNumber($rowAvailable, $rowsReturned) {
		$iterationDouble = (double) ($rowAvailable / $rowsReturned);
		$iterationInt = (int) ($rowAvailable / $rowsReturned);
		if ($iterationDouble > $iterationInt) {
			$iterationInt++;
		}
		return $iterationInt;
	}
}
