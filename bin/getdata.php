<?php
require_once realpath(dirname(__FILE__)) . '/../app/start.php';

use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;

$app_id = "8lsOjMwbnPUFRjhS13k1XoJFxVZbN6f8vpkzLEJ8";
$rest_key = "FL5NyJhfPzsVPyQQMNZEXFIJmLQcC3PtkzDuINQ9";
$master_key = "7QsjpROc3Nb853O6IPceMQJHSk2CBTmnIWBPsbot";

ParseClient::initialize( $app_id, $rest_key, $master_key );

$query = new ParseQuery("test");



// Get a specific object:
//$object = $query->get("anObjectId");

$query->limit(100); // default 100, max 1000
$query->addDescending('updateAt');
// All results:
$results = $query->find();
foreach($results as $item) {
    echo $item->getObjectId()."\n";
    echo $item->get('score')."\n";
    echo $item->get('phone')."\n";

}

//var_dump($results);die();
