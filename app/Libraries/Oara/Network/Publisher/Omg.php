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
 * @category   Oara_Network_Publisher_Omg
 * @copyright  Fubra Limited
 * @version    Release: 01.00
 *
 */
class Oara_Network_Publisher_Omg extends Oara_Network {
	/**
	 * Export Merchants Parameters
	 *
	 * @var array
	 */
	private $_exportMerchantParameters = null;
	/**
	 * Export Transaction Parameters
	 *
	 * @var array
	 */
	private $_exportTransactionParameters = null;
	/**
	 * Export Overview Parameters
	 *
	 * @var array
	 */
	private $_exportOverviewParameters = null;
	/**
	 * Export Payment Parameters
	 *
	 * @var array
	 */
	private $_exportPaymentParameters = null;
	/**
	 * Export Transaction Payment Parameters
	 *
	 * @var array
	 */
	private $_exportPaymentTransactionParameters = null;
	
	private $_agency = null;

	/**
	 * Client
	 *
	 * @var unknown_type
	 */
	private $_client = null;
	/**
	 * Constructor and Login
	 *
	 * @param
	 *        	$omg
	 * @return Oara_Network_Publisher_Omg_Export
	 */
	public function __construct($credentials) {
		$user = $credentials ['user'];
		$password = $credentials ['password'];

		$loginUrl = 'https://admin.omgpm.com/en/clientarea/login_welcome.asp';

		$contact = null;

		$exportPass = null;

		$valuesLogin = array (
		new Oara_Curl_Parameter ( 'emailaddress', $user ),
		new Oara_Curl_Parameter ( 'password', $password ),
		new Oara_Curl_Parameter ( 'Submit', 'Sign in' )
		);

		$this->_client = new Oara_Curl_Access ( $loginUrl, $valuesLogin, $credentials );


	}
	/**
	 * Check the connection
	 */
	public function checkConnection() {
		// If not login properly the construct launch an exception
		$connection = true;

		try{
			$this->_exportMerchantParameters = array (
			new Oara_Curl_Parameter ( 'searchcampaigns', '' ),
			new Oara_Curl_Parameter ( 'ProductTypeID', '0' ),
			new Oara_Curl_Parameter ( 'SectorID', '0' ),
			new Oara_Curl_Parameter ( 'CountryIDProgs', '0' ),
			new Oara_Curl_Parameter ( 'ProgammeStatus', 'live' ),
			new Oara_Curl_Parameter ( 'geturl', 'Get+URL' ),
			new Oara_Curl_Parameter ( 'ExportFormat', 'XML' )
			);
				
			$valuesFromExport = $this->_exportMerchantParameters;
			$urls = array ();
			$urls [] = new Oara_Curl_Request ( 'https://admin.omgpm.com/en/clientarea/affiliates/affiliate_campaigns.asp?', $valuesFromExport );
			$exportReport = $this->_client->post ( $urls );
			/**
			 * * load the html into the object **
			 */
			$doc = new DOMDocument ();
			libxml_use_internal_errors ( true );
			$doc->validateOnParse = true;
			$doc->loadHTML ( $exportReport [0] );
			$textareaList = $doc->getElementsByTagName ( 'textarea' );
				
			$messageNode = $textareaList->item ( 0 );
			if (! isset ( $messageNode->firstChild )) {
				throw new Exception ( 'Error getting the Merchants' );
			}
			$messageStr = $messageNode->firstChild->nodeValue;
				
			$parseUrl = parse_url ( trim ( $messageStr ) );
				
			$parameters = explode ( '&', $parseUrl ['query'] );
			$oaraCurlParameters = array ();
			foreach ( $parameters as $parameter ) {
				$parameterValue = explode ( '=', $parameter );
				if ($parameterValue [0] == 'Affiliate') {
					$contact = $parameterValue [1];
				}
				if ($parameterValue [0] == 'AuthHash') {
					$exportPass = $parameterValue [1];
				}
			}
			if ($contact == null || $exportPass == null) {
				throw new Exception ( "Member id doesn\'t found" );
			}
				
			$this->_exportTransactionParameters = array (
			new Oara_Curl_Parameter ( 'Contact', $contact ),
			new Oara_Curl_Parameter ( 'Status', '-1' ),
			new Oara_Curl_Parameter ( 'DateType', '2' ),
			new Oara_Curl_Parameter ( 'Login', $exportPass ),
			new Oara_Curl_Parameter ( 'Format', 'CSV' )
			);
				
			$this->_exportPaymentParameters = array (
			new Oara_Curl_Parameter ( 'ctl00$Uc_Navigation1$ddlNavSelectMerchant', '0' ),
			new Oara_Curl_Parameter ( 'ctl00$ContentPlaceHolder1$ddlMonth', '0' ),
			new Oara_Curl_Parameter ( 'ctl00$ContentPlaceHolder1$ddlStatus', 'All' ),
			new Oara_Curl_Parameter ( 'ctl00$ContentPlaceHolder1$btnSearch', 'Search' )
			);
				
			$this->_exportPaymentTransactionParameters = array (
			new Oara_Curl_Parameter ( 'Contact', $contact ),
			new Oara_Curl_Parameter ( 'Login', $exportPass ),
			new Oara_Curl_Parameter ( 'Format', 'XML' )
			);
				
		} catch(Exception $e){
			$connection = false;
		}

		return $connection;
	}
	/**
	 * (non-PHPdoc)
	 *
	 * @see library/Oara/Network/Oara_Network_Publisher_Interface#getMerchantList()
	 */
	public function getMerchantList($merchantMap = array()) {
		$merchants = array ();
		$merchantsExport = self::getMerchantExport ();
		foreach ( $merchantsExport as $merchantData ) {
			$obj = Array ();
			$obj ['cid'] = $merchantData ['cid'];
			$obj ['name'] = $merchantData ['name'];
			$obj ['description'] = $merchantData ['description'];
			$obj ['url'] = $merchantData ['url'];
			$merchants [] = $obj;
		}
		return $merchants;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see library/Oara/Network/Oara_Network_Publisher_Interface#getTransactionList($aMerchantIds, $dStartDate, $dEndDate, $sTransactionStatus)
	 */
	public function getTransactionList($merchantList = null, Zend_Date $dStartDate = null, Zend_Date $dEndDate = null, $merchantMap = null) {
		$dStartDate = clone $dStartDate;
		$dStartDate->setHour ( "00" );
		$dStartDate->setMinute ( "00" );
		$dStartDate->setSecond ( "00" );
		$dEndDate = clone $dEndDate;
		$dEndDate->setHour ( "23" );
		$dEndDate->setMinute ( "59" );
		$dEndDate->setSecond ( "59" );

		$urls = array ();
		$urls [] = new Oara_Curl_Request ( 'https://admin.omgpm.com/v2/reports/affiliate/leads/LeadBreakdownExportUrl.aspx?', array() );
		$exportReport = $this->_client->get ( $urls );

		$countryMap = array();
		$dom = new Zend_Dom_Query($exportReport[0]);
		$results = $dom->query('#ContentPlaceHolder1_DDLCountry');
		$countryLines = $results->current()->childNodes;
		for ($i = 0; $i < $countryLines->length; $i++) {
			$cid = $countryLines->item($i)->attributes->getNamedItem("value")->nodeValue;
			if (is_numeric($cid)){
				$countryMap[$cid] = $countryLines->item($i)->nodeValue;
			}
		}
		foreach ($countryMap as $countryId => $countryName){
			
			$valuesFromExport = Oara_Utilities::cloneArray ( $this->_exportTransactionParameters );
			$valuesFromExport [] = new Oara_Curl_Parameter ( 'Year', $dStartDate->get ( Zend_Date::YEAR ) );
			$valuesFromExport [] = new Oara_Curl_Parameter ( 'Month', $dStartDate->get ( Zend_Date::MONTH ) );
			$valuesFromExport [] = new Oara_Curl_Parameter ( 'Day', $dStartDate->get ( Zend_Date::DAY ) );
			$valuesFromExport [] = new Oara_Curl_Parameter ( 'EndYear', $dEndDate->get ( Zend_Date::YEAR ) );
			$valuesFromExport [] = new Oara_Curl_Parameter ( 'EndMonth', $dEndDate->get ( Zend_Date::MONTH ) );
			$valuesFromExport [] = new Oara_Curl_Parameter ( 'EndDay', $dEndDate->get ( Zend_Date::DAY ) );
			$valuesFromExport [] = new Oara_Curl_Parameter ( 'Country',  $countryId );
			$valuesFromExport [] = new Oara_Curl_Parameter ( 'Agency',  $this->_agency );

			$transactions = Array ();
			$urls = array ();
			$urls [] = new Oara_Curl_Request ( 'https://admin.omgpm.com/v2/reports/affiliate/leads/leadbreakdownexport.aspx?', $valuesFromExport );
			$exportReport = $this->_client->get ( $urls );
			$exportData = str_getcsv ( $exportReport [0], "\n" );

			$rowNumber = 0;
			foreach ( $exportData as $exportRow ) {
				if ($rowNumber > 0 && $rowNumber != count($exportData) - 1) {
					$row = str_getcsv ( $exportRow, "," );
					$date = new Zend_Date ( $row [9], "dd-MM-yyyy HH:mm:ss" );
					if (in_array ( ( int ) $row [10], $merchantList )) {
							
						$obj ['unique_id'] = $row [1];
						$obj ['merchantId'] = $row [10];
						$obj ['date'] = $date->toString ( "yyyy-MM-dd HH:mm:ss" );
							
						$obj ['amount'] = 0;
						$obj ['commission'] = 0;
							
						if ($row [3] != null) {
							$obj ['custom_id'] = $row [3];
						}
							
						if ($row [14] != null) {
							$obj ['amount'] = Oara_Utilities::parseDouble ( $row [14] );
						}
						if ($row [16] != null) {
							$obj ['commission'] = Oara_Utilities::parseDouble ( $row [16] );
						}
							
						if ($row [15] == 'Validated') {
							$obj ['status'] = Oara_Utilities::STATUS_CONFIRMED;
						} else if ($row [15] == 'Pending') {
							$obj ['status'] = Oara_Utilities::STATUS_PENDING;
						} else if ($row [15] == 'Rejected') {
							$obj ['status'] = Oara_Utilities::STATUS_DECLINED;
						}
							
						$transactions [] = $obj;
					} else {
						// echo "Merchant:".$row[7]."(".$row[6].")"." Product:".$row[8]."(".$row[9].")\n\n";
					}
				}
				$rowNumber ++;
			}
		}
		return $transactions;
	}

	/**
	 * Gets all the merchants and returns them in an array.
	 *
	 * @return array
	 */
	private function getMerchantExport() {
		$merchants = array ();
		$valuesFromExport = $this->_exportMerchantParameters;
		$merchantsAux = Array ();
		$urls = array ();

		$urls [] = new Oara_Curl_Request ( 'https://admin.omgpm.com/en/clientarea/affiliates/affiliate_campaigns.asp?', $valuesFromExport );

		$exportReport = $this->_client->get ( $urls );

		$doc = new DOMDocument ();
		libxml_use_internal_errors ( true );
		$doc->validateOnParse = true;
		$doc->loadHTML ( $exportReport [0] );
		$textareaList = $doc->getElementsByTagName ( 'textarea' );

		$messageNode = $textareaList->item ( 0 );
		if (! isset ( $messageNode->firstChild )) {
			throw new Exception ( 'Error getting the Merchants' );
		}
		$messageStr = $messageNode->firstChild->nodeValue;

		$parseUrl = parse_url ( trim ( $messageStr ) );
		$parameters = explode ( '&', $parseUrl ['query'] );
		$oaraCurlParameters = array ();
		foreach ( $parameters as $parameter ) {
			$parameterValue = explode ( '=', $parameter );
			if ($parameterValue [0] == "Agency"){
				$this->_agency = $parameterValue [1];
			}
			$oaraCurlParameters [] = new Oara_Curl_Parameter ( $parameterValue [0], $parameterValue [1] );
		}
		$urls = array ();
		$urls [] = new Oara_Curl_Request ( $parseUrl ['scheme'] . '://' . $parseUrl ['host'] . $parseUrl ['path'] . '?', $oaraCurlParameters );

		$exportReport = $this->_client->get ( $urls );
		$xml = self::loadXml ( $exportReport [0] );
		if (isset ( $xml->table1 ) && isset ( $xml->table1->Detail_Collection ) && isset ( $xml->table1->Detail_Collection->Detail )) {
				
			foreach ( $xml->table1->Detail_Collection->Detail as $merchantData ) {
				if ($merchantData ['ProgrammeStatus'] == 'Live') {
					$obj = Array ();
					$parseUrl = parse_url ( trim ( self::findAttribute ( $merchantData, 'TrackingURL' ) ) );
					$parameters = explode ( '&', $parseUrl ['query'] );
					$enc = false;
					$i = 0;
					while ( ! $enc && $i < count ( $parameters ) ) {
						$parameterValue = explode ( '=', $parameters [$i] );
						if ($parameterValue [0] == 'PID') {
							$obj ['cid'] = $parameterValue [1];
							$enc = true;
						}
						$i ++;
					}
					if (! $enc) {
						throw new Exception ( 'Merchant without MID' );
					}
					if (! isset ( $merchantsAux [$obj ['cid']] )) {
						$obj ['name'] = self::findAttribute ( $merchantData, 'ProductName' ) . " (" . self::findAttribute ( $merchantData, 'MerchantName' ) . ")";
						$obj ['description'] = self::findAttribute ( $merchantData, 'ProductDescription' );
						$obj ['url'] = self::findAttribute ( $merchantData, 'WebsiteURL' );
						$obj ['sector'] = self::findAttribute ( $merchantData, 'Sector' );
						$merchantsAux [$obj ['cid']] = $obj;
					}
				}
			}
			foreach ( $merchantsAux as $id => $merchant ) {
				$merchants [] = $merchant;
			}
		}

		return $merchants;
	}

	/**
	 * Cast the XMLSIMPLE object into string
	 *
	 * @param
	 *        	$object
	 * @param
	 *        	$attribute
	 * @return unknown_type
	 */
	private function findAttribute($object = null, $attribute = null) {
		$return = null;
		$return = trim ( $object [$attribute] );
		return $return;
	}
	/**
	 * Convert the string in xml object.
	 *
	 * @param
	 *        	$exportReport
	 * @return xml
	 */
	private function loadXml($exportReport = null) {
		$xml = simplexml_load_string ( $exportReport, null, LIBXML_NOERROR | LIBXML_NOWARNING );
		if ($xml == false) {
			throw new Exception ( 'Problems in the XML' );
		}
		return $xml;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see Oara/Network/Oara_Network_Publisher_Base#getPaymentHistory()
	 */
	public function getPaymentHistory() {
		$paymentHistory = array ();
		$valuesFromExport = $this->_exportPaymentParameters;

		$urls = array ();
		$urls [] = new Oara_Curl_Request ( 'https://admin.omgpm.com/v2/finance/affiliate/view_payments.aspx?', array () );
		$exportReport = $this->_client->get ( $urls );
		/**
		 * * load the html into the object **
		 */
		$doc = new DOMDocument ();
		libxml_use_internal_errors ( true );
		$doc->validateOnParse = true;
		$doc->loadHTML ( $exportReport [0] );
		$hiddenList = $doc->getElementsByTagName ( 'input' );
		if ($hiddenList->length > 0) {
				
			for($i = 0; $i < $hiddenList->length; $i ++) {
				$attrs = $hiddenList->item ( $i )->attributes;
				if ($attrs->getNamedItem ( "type" )->nodeValue == 'hidden') {
					// we are adding the hidden parameters
					$valuesFromExport [] = new Oara_Curl_Parameter ( $attrs->getNamedItem ( "name" )->nodeValue, $attrs->getNamedItem ( "value" )->nodeValue );
				}
			}
			$yearStart = 2011;
			$nowDays = new Zend_Date ();
			$yearEnd = ( int ) $nowDays->get ( Zend_Date::YEAR );
				
			$urls = array ();
			for($i = $yearStart; $i <= $yearEnd; $i ++) {
				$requestValuesFromExport = Oara_Utilities::cloneArray ( $valuesFromExport );
				$requestValuesFromExport [] = new Oara_Curl_Parameter ( 'ctl00$ContentPlaceHolder1$ddlYear', ( string ) $i );
				$urls [] = new Oara_Curl_Request ( 'https://admin.omgpm.com/v2/finance/affiliate/view_payments.aspx?', $requestValuesFromExport );
			}
			$exportReport = $this->_client->post ( $urls );
			for($i = 0; $i < count ( $exportReport ); $i ++) {
				if (! preg_match ( "/No Results for this criteria/i", $exportReport [$i] )) {
					$doc = new DOMDocument ();
					libxml_use_internal_errors ( true );
					$doc->validateOnParse = true;
					$doc->loadHTML ( $exportReport [$i] );
					$table = $doc->getElementById ( 'ContentPlaceHolder1_gvSummary' );
					$paymentList = $table->childNodes;
					for($j = 1; $j < $paymentList->length; $j ++) {
						$paymentData = $paymentList->item ( $j )->childNodes;

						$obj = array ();
						$obj ['value'] = Oara_Utilities::parseDouble ( $paymentData->item ( 5 )->nodeValue );
						if ($obj ['value'] != null) {
							$date = new Zend_date ( $paymentData->item ( 8 )->nodeValue, "dd/MM/yyyy HH:mm:ss" );
							$obj ['date'] = $date->toString ( "yyyy-MM-dd HH:mm:ss" );
							$obj ['pid'] = $paymentData->item ( 2 )->nodeValue;
							$obj ['method'] = 'BACS';
							$paymentHistory [] = $obj;
						}
					}
				}
			}
		}
		return $paymentHistory;
	}

	/**
	 * It returns the transactions for a payment
	 *
	 * @see Oara_Network::paymentTransactions()
	 */
	public function paymentTransactions($paymentId, $merchantList, $startDate) {
		$paymentTransactionList = array ();
		$urls = array ();
		$valuesFromExport = $this->_exportPaymentTransactionParameters;
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'InvoicePayoutID', "$paymentId," );
		$valuesFromExport [] = new Oara_Curl_Parameter ( 'Agency',  $this->_agency );
		$urls [] = new Oara_Curl_Request ( 'https://admin.omgpm.com/v2/reports/affiliate/leads/leadsbyinvoiceexport.aspx?', $valuesFromExport );
		$exportReport = $this->_client->get ( $urls );
		$xml = self::loadXml ( $exportReport [0] );
		if (isset ( $xml->Leads->Detail_Collection )) {
			foreach ( $xml->Leads->Detail_Collection->Detail as $detail ) {
				$paymentTransactionList [] = self::findAttribute ( $detail, 'AppID' );
			}
		}

		return $paymentTransactionList;
	}
}
