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
 * @category Oara_Network_Publisher_DirectTrack
 * @copyright Fubra Limited
 * @version Release: 01.00
 *         
 *         
 */
class Oara_Network_Publisher_DirectTrack extends Oara_Network {
	/**
	 * Soap client.
	 */
	private $_apiClient = null;
	
	/**
	 * Client
	 */
	private $_client = null;
	/**
	 * Password
	 */
	private $_password = null;
	/**
	 * Publisher
	 */
	private $_publisherId = null;
	/**
	 * Params
	 */
	private $_params = null;
	
	/**
	 * Constructor.
	 *
	 * @param
	 *        	$affiliateWindow
	 * @return Oara_Network_Publisher_Aw_Api
	 */
	public function __construct($credentials) {
		ini_set ( 'default_socket_timeout', '120' );
		$this->_version = '1_0';
		$this->_domain = $credentials["domain"];
		$this->_clientId  = $credentials["client"];
		$this->_accessId = $credentials["access"];
		$this->_username = $credentials["user"];
		$this->_password = $credentials["password"];
		
	}
	/**
	 * Check the connection
	 */
	public function checkConnection() {
		$connection = false;
		$apiURL = "https://{$this->_domain}/apifleet/rest/{$this->_clientId}/{$this->_accessId}/campaign/active/";
		$response = self::call($apiURL);
		if (isset($response["@attributes"])){
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
		$obj = Array ();
		$obj ['cid'] = "1";
		$obj ['name'] = "DirectTrack";
		$merchants [] = $obj;
		return $merchants;
	}
	/**
	 * (non-PHPdoc)
	 *
	 * @see library/Oara/Network/Oara_Network_Publisher_Base#getTransactionList($merchantId,$dStartDate,$dEndDate)
	 */
	public function getTransactionList($merchantList = null, Zend_Date $dStartDate = null, Zend_Date $dEndDate = null, $merchantMap = null) {
		$totalTransactions = array ();
		
		$dateArray = Oara_Utilities::daysOfDifference($dStartDate, $dEndDate);
		foreach ($dateArray as $date){
				
				$apiURL = "https://{$this->_domain}/apifleet/rest/{$this->_clientId}/{$this->_accessId}/statCampaign/quick/{$date->toString("yyyy-MM-dd")}";
				$response = self::call($apiURL);
			
				if (isset($response["resource"]["numSales"])){
						
					$transaction = Array ();
					$transaction ['merchantId'] = "1";
					$transaction ['date'] = $date->toString ( "yyyy-MM-dd HH:mm:ss" );
					$transaction ['status'] = Oara_Utilities::STATUS_CONFIRMED;
						
					$transaction ['amount'] = $response["resource"]["saleAmount"];
					$transaction ['commission'] = $response["resource"]["theyGet"];
					$transaction ['currency'] = $response["resource"]["currency"];
					
					if ($transaction ['amount'] != 0 && $transaction ['commission'] != 0) {
						$totalTransactions [] = $transaction;
					}
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
		$headers[] = "Authorization: Basic ".base64_encode($this->_username.":".$this->_password);
		
		// Initiate the REST call via curl
		$ch = curl_init($apiUrl);
			
		// Set the HTTP method to GET
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		// Add the headers defined above
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// Don't return headers
		curl_setopt($ch, CURLOPT_HEADER, false);
		// Return data after call is made
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		// Execute the REST call
		$response = curl_exec($ch);
		$data = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
		$json = json_encode($data);
		$array = json_decode($json, true);
		// Close the connection
		curl_close($ch);
		return $array;
	}
}