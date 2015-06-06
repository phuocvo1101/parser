<?php
require_once realpath(dirname(__FILE__)) . '/../app/start.php';

use \Parse\ParseClient;
use \Parse\ParsePush;
use \Parse\ParseInstallation;

$app_id = "8lsOjMwbnPUFRjhS13k1XoJFxVZbN6f8vpkzLEJ8";
$rest_key = "FL5NyJhfPzsVPyQQMNZEXFIJmLQcC3PtkzDuINQ9";
$master_key = "7QsjpROc3Nb853O6IPceMQJHSk2CBTmnIWBPsbot";



ParseClient::initialize( $app_id, $rest_key, $master_key );
$query = ParseInstallation::query();
$platforms = array('android'
//,'ios','winrt','winphone'
);
$query->containedIn('deviceType',$platforms);
$result = ParsePush::send(array(
    "where" => $query,
    "data" => array(
        "alert" => "Quang test restful api 1"
    )
));

/*
 *  ParsePush::send(
            [
            'channels' => [''],
            'data'     => ['alert' => 'sample message'],
            ]
        );
 */
var_dump($result);die();