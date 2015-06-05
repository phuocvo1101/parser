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
class TestModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $app_id = "8lsOjMwbnPUFRjhS13k1XoJFxVZbN6f8vpkzLEJ8";
        $rest_key = "FL5NyJhfPzsVPyQQMNZEXFIJmLQcC3PtkzDuINQ9";
        $master_key = "7QsjpROc3Nb853O6IPceMQJHSk2CBTmnIWBPsbot";

        ParseClient::initialize( $app_id, $rest_key, $master_key );



    }
    public function listTest($start=0, $limit = 0,$search='')
    {
        $query = new ParseQuery("test");
        if($start ==0) {
            $skip = $start;
        } else{
            $skip = $start - 1;
        }
        if($search==''){
            $count = $query->count();
        }else{
            $query->equalTo("phone", $search); // conditional
            $count = $query->count();

        }

        $query->skip($skip);
        if($limit !=0){
            $query->limit($limit); // default 100, max 1000
        }

        $query->addDescending('updateAt');
        if($search!=''){
            $query->equalTo("phone", $search);
        }

// All results:
        $results = $query->find();
       // return $results;
        $arr= array();
        foreach($results as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'mode'=>$item->get('mode'),
                'name'=>$item->get('name'),
                'object1'=>$item->get('object1'),
                'score'=>$item->get('score'),
                'phone'=>$item->get('phone'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),

            ));

        }

//echo '<pre>'.print_r($arr,true).'</pre>';die();
        return array('data'=>$arr,'count'=>$count);
    }

}