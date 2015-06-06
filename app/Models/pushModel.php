<?php
namespace Models;

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
class PushModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $app_id = "8lsOjMwbnPUFRjhS13k1XoJFxVZbN6f8vpkzLEJ8";
        $rest_key = "FL5NyJhfPzsVPyQQMNZEXFIJmLQcC3PtkzDuINQ9";
        $master_key = "7QsjpROc3Nb853O6IPceMQJHSk2CBTmnIWBPsbot";

        ParseClient::initialize( $app_id, $rest_key, $master_key );

    }
    public function insertMessage($data)
    {
        
            $query = 'INSERT INTO pushnotifications(status,target,name,time)
             VALUES(?,?,?,?)';
             $this->database->setQuery($query);
            $arrData = array(
                array($data['status'],\PDO::PARAM_INT),
                array($data['target'],\PDO::PARAM_STR),
                array($data['name'],\PDO::PARAM_STR),
                array($data['time'],\PDO::PARAM_INT)
            );
            
            $result=$this->database->execute($arrData);
        
            if(!$result){
                return false;
            }
        return true;
    }
    public function getdataMessage()
    {
        $query = 'SELECT * FROM pushnotifications ORDER BY `time` desc';
        $this->database->setQuery($query);
        $result= $this->database->loadAllRows();
       // echo '<pre>'.print_r($result,true).'</pre>';die();
        if(!$result){
            return false;
        }
        return $result;

    }
    
    public function PushToMessage($mess,$chanel='')
    {
        $query = ParseInstallation::query();
        $platforms = array('android'
            //,'ios','winrt','winphone'
        );
        $query->containedIn('deviceType',$platforms);
        if($chanel != ''){
            $query->equalTo("channels", $chanel);
        }
        $result = ParsePush::send(array(
            "where" => $query,
            "data" => array(
                "alert" => $mess
            )
        ));
       // var_dump($result);die();
        if(!$result){
            return false;
        }
        return true;
    }


}