<?php
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

require_once 'app/start.php';
$client = new \GuzzleHttp\Client(['cookies' => true]);



$response = $client->post('https://reports.tradedoubler.com/pan/login',[
    'form_params' => [
        'j_username' => 'azzarev',
        'j_password' => 'emanuele81'
    ]
]);
echo '<pre>'.print_r($response->getBody(true)->getContents(),true).'</pre>';die();
$url = 'https://reports.tradedoubler.com/pan/aReport3.action?reportName=aAffiliateEventBreakdownReport&columns=timeOfVisit&columns=timeOfEvent&columns=timeInSession&columns=lastModified&columns=epi1&columns=eventName&columns=pendingStatus&columns=siteName&columns=graphicalElementName&columns=productName&columns=productNrOf&columns=productValue&columns=open_product_feeds_id&columns=open_product_feeds_name&columns=voucher_code&columns=deviceType&columns=os&columns=browser&columns=vendor&columns=device&columns=affiliateCommission&columns=link&columns=leadNR&columns=orderNR&columns=pendingReason&columns=orderValue&startDate=01/04/15&endDate=29/05/15&isPostBack=&metric1.lastOperator=/&interval=&favoriteDescription=&currencyId=EUR&event_id=0&pending_status=1&run_as_organization_id=&minRelativeIntervalStartTime=0&includeWarningColumn=true&metric1.summaryType=NONE&includeMobile=1&latestDayToExecute=0&metric1.operator1=/&showAdvanced=false&breakdownOption=1&metric1.midFactor=&reportTitleTextKey=REPORT3_SERVICE_REPORTS_AAFFILIATEEVENTBREAKDOWNREPORT_TITLE&metric1.columnName1=orderValue&setColumns=true&metric1.columnName2=orderValue&reportPrograms=&metric1.midOperator=/&period=custom_period&dateType=1&dateSelectionType=1&favoriteName=&affiliateId=&tabMenuName=&maxIntervalSize=0&emptyPlaceHolder_0=&favoriteId=&sortBy=timeOfEvent&metric1.name=&filterOnTimeHrsInterval=false&customKeyMetricCount=0&metric1.factor=&showFavorite=false&separator=&format=XML';

$r = $client->get($url);

$stream = $r->getBody(true);

$metadata = $stream->getContents();

var_dump($metadata);die();

die();