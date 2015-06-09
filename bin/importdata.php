<?php
require_once realpath(dirname(__FILE__)) . '/../app/start.php';
require realpath(dirname(__FILE__)) . '/settings.php';
$networkName = "TradeDoubler"; //Ex: AffiliateWindow
//Retrieving the credentials for the network

$readOptions = true;
$optionsWithData = array('m');
$longOptionsWithData = array('month');
$month = '';
for ($i = 1; $i < count($argv); ++$i) {
    $arg = $argv[$i];
    if ($readOptions and
        strlen($arg) > 0 and
        $arg[0] == '-'
    ) {
        if (strlen($arg) > 1 and
            $arg[1] == '-'
        ) {
            $flag = substr($arg, 2);
            if (in_array($flag, $longOptionsWithData)) {
                $optionData = $argv[$i + 1];
                ++$i;
            }
            if ($flag == 'help') {
                help();
                exit();
            } else if ($flag == 'month') {
                $month = $optionData;
            }

        } else {
            $flag = substr($arg, 1, 1);
            $optionData = false;
            if (in_array($flag, $optionsWithData)) {
                if (strlen($arg) > 2) {
                    $optionData = substr($arg, 2);
                } else {
                    $optionData = $argv[$i + 1];
                    ++$i;
                }
            }
            if ($flag == 'h') {
                help();
                exit();
            } else if ($flag == 'm') {
                $month = $optionData;
            }
        }
    }

}

if($month=='') {
    $month=1;
}

$configName = strtolower($networkName);
$config = Zend_Registry::getInstance()->get('credentialsIni');

$configName = strtolower($networkName);
$credentials = $config->$configName->toArray();

//Path for the cookie located inside of COOKIES_BASE_DIR
$credentials["cookiesDir"] = "azzarev";
$credentials["cookiesSubDir"] = $networkName;
$credentials["cookieName"] = "test";

//The name of the network, It should be the same that the class inside Oara/Network
$credentials['networkName'] = $networkName;
//Which point of view "Publisher" or "Advertiser"
$credentials['type'] = "Publisher";
//The Factory creates the object
$network = Oara_Factory::createInstance($credentials);
//Oara_Test::testNetwork($network);

$data = new \Models\GetDataModel();
$transactions = $data->getData($network,$month);

$report = new \Models\ReportModel();
$result = $report->insertReport($transactions);
if($result) {
    echo "Import was successfull";
} else {
    echo "Import was failed!";
}

function help()
{
    $argv = $_SERVER['argv'];
    echo "Usage: " . $argv[0] . " [OPTION]... [PART]","green";
    echo "\n";
    echo "Executes cronjobs.","green";
    echo "\n";
    echo "General options:","green";
    echo "\n";
    echo "  -h,--help          display this help and exit ","green";
    echo "\n";
    echo "  -m,--month          get data follow month ","green";
    echo "\n";

}
