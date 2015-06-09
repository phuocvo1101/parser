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
 * @author Carlos Morillo Merino
 * @category Oara_Network_Publisher_GetCake
 * @copyright Fubra Limited
 * @version Release: 01.00
 *         
 *         
 */
class Oara_Network_Publisher_GetCake extends Oara_Network {
	/**
	 * Client
	 */
	private $_user = null;
	/**
	 * Domain
	 */
	private $_domain = null;
	/**
	 * Password
	 */
	private $_apiPassword = null;
	
	/**
	 * Constructor.
	 *
	 * @param
	 *        	$affiliateWindow
	 * @return Oara_Network_Publisher_Aw_Api
	 */
	public function __construct($credentials) {
		ini_set ( 'default_socket_timeout', '120' );
		
		$this->_domain = $credentials["domain"];
		$this->_user = $credentials["user"];
		$this->_apiPassword = $credentials["apiPassword"];
		
	}
	/**
	 * Check the connection
	 */
	public function checkConnection() {
		$connection = false;
		
		
		$apiURL = "http://{$this->_domain}/affiliates/api/4/offers.asmx/OfferFeed?api_key={$this->_apiPassword}&affiliate_id={$this->_user}&campaign_name=&media_type_category_id=0&vertical_category_id=0&vertical_id=0&offer_status_id=0&tag_id=0&start_at_row=1&row_limit=0";
		$response = self::call($apiURL);
		if ($response["success"] == "true"){
			$connection = true;
		}
		return $connection;
	}
	/**
	 * (non-PHPdoc)
	 *
	 * @see library/Oara/Network/Oara_Network_Publisher_Base#getMerchantList()
	 */
	public function getMerchantList() {
		$merchants = array ();
		$apiURL = "http://{$this->_domain}/affiliates/api/4/offers.asmx/OfferFeed?api_key={$this->_apiPassword}&affiliate_id={$this->_user}&campaign_name=&media_type_category_id=0&vertical_category_id=0&vertical_id=0&offer_status_id=0&tag_id=0&start_at_row=1&row_limit=0";
		$response = self::call($apiURL);
				
		foreach ($response["offers"]["offer"] as $merchant){
			
			$obj = Array ();
			$obj ['cid'] = $merchant["offer_id"];
			$obj ['name'] = $merchant["offer_name"];
			$merchants [] = $obj;
		}
		
		return $merchants;
	}
	/**
	 * (non-PHPdoc)
	 *
	 * @see library/Oara/Network/Oara_Network_Publisher_Base#getTransactionList($merchantId,$dStartDate,$dEndDate)
	 */
	public function getTransactionList($merchantList = null, Zend_Date $dStartDate = null, Zend_Date $dEndDate = null, $merchantMap = null) {
		$totalTransactions = array ();
		
		$rowIndex = 1;
		$rowCount = 100;
		$request = true;
		while($request){
			$apiURL = "http://{$this->_domain}/affiliates/api/5/reports.asmx/Conversions?api_key={$this->_apiPassword}&affiliate_id={$this->_user}&start_date=".urlencode($dStartDate->toString("yyyy-MM-dd HH:mm:ss"))."&end_date=".urlencode($dEndDate->toString("yyyy-MM-dd HH:mm:ss"))."&offer_id=0&start_at_row=$rowIndex&row_limit=$rowCount";
			$response = self::call($apiURL);
			
			if (isset($response["conversions"]["conversion"])){
				foreach ($response["conversions"]["conversion"] as $transactionApi){
				
					$transaction = Array();
					$merchantId = (int) $transactionApi["offer_id"];
						
					if (in_array($merchantId, $merchantList)){
						$transaction['merchantId'] = $merchantId;
							
						$transactionDate = new Zend_Date($transactionApi["conversion_date"], 'yyyy-MM-ddTHH:mm:ss', 'en');
						$transaction['date'] = $transactionDate->toString("yyyy-MM-dd HH:mm:ss");
						$transaction ['uniqueId'] = $transactionApi["order_id"];
						if (count($transactionApi["subid_1"]) > 0) {
							$transaction['custom_id'] = implode(",", $transactionApi["subid_1"]);
						}
							
						if ($transactionApi["disposition"] == "Approved") {
							$transaction['status'] = Oara_Utilities::STATUS_CONFIRMED;
						} else
						if ($transactionApi["disposition"] == "Pending" || $transactionApi["disposition"] == null) {
							$transaction['status'] = Oara_Utilities::STATUS_PENDING;
						} else
						if ($transactionApi["disposition"] == "Rejected") {
							$transaction['status'] = Oara_Utilities::STATUS_DECLINED;
						}
							
						$transaction['amount'] = $transactionApi["price"];
						$transaction['commission'] = $transactionApi["price"];
						$totalTransactions[] = $transaction;
				
					}
				}
				
			}
			if (count($response["conversions"]) == $rowCount){
				$rowIndex = $rowIndex + $rowCount;
			} else {
				$request = false;
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
	
	private function call($apiUrl){
		
		// Initiate the REST call via curl
		$ch = curl_init($apiUrl);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:26.0) Gecko/20100101 Firefox/26.0");
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		// Set the HTTP method to GET
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		// Don't return headers
		curl_setopt($ch, CURLOPT_HEADER, false);
		// Return data after call is made
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		// Execute the REST call
		$response = curl_exec($ch);
		
		
		
		$xml = simplexml_load_string($response);
		$json = json_encode($xml);
		$array = json_decode($json, true);
		// Close the connection
		curl_close($ch);
		return $array;
	}
}