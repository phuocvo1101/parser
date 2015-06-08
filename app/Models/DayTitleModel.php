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
class DayTitleModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $app_id = "8lsOjMwbnPUFRjhS13k1XoJFxVZbN6f8vpkzLEJ8";
        $rest_key = "FL5NyJhfPzsVPyQQMNZEXFIJmLQcC3PtkzDuINQ9";
        $master_key = "7QsjpROc3Nb853O6IPceMQJHSk2CBTmnIWBPsbot";

        ParseClient::initialize( $app_id, $rest_key, $master_key );



    }
    public function dayTitleId($id)
    {
        $query = new ParseQuery("DayTitle");
        $query->get($id);
        $result = $query->find();

        $arr= array();
        foreach($result as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'Title'=>$item->get('Title'),
                'TitleDate'=>$item->get('TitleDate'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),


            ));

        }
        // echo '<pre>'.print_r($arr,true).'<//pre>' ;die();
        return $arr;
    }
    public function listDayTitle($start=0, $limit = 0,$search='')
    {
        $query = new ParseQuery("DayTitle");
        if($start ==0) {
            $skip = $start;
        } else{
            $skip = $start - 1;
        }
        if($search==''){
            $count = $query->count();
        }else{
            $query->equalTo("Title", $search); // conditional
            $count = $query->count();

        }

        $query->skip($skip);
        if($limit !=0){
            $query->limit($limit); // default 100, max 1000
        }
        // $query->addDescending('updateAt')->addDescending('createAt');
        if($search!=''){
            $query->equalTo("Title", $search);
        }

// All results:
        $query->descending("updateAt");
        $query->descending("createdAt");
        $results = $query->find();

        // return $results;
        $arr= array();
        foreach($results as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'Title'=>$item->get('Title'),
                'TitleDate'=>$item->get('TitleDate'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),

            ));

        }
        //  echo '<pre>'.print_r($arr,true).'</pre>';die();

//echo '<pre>'.print_r($arr,true).'</pre>';die();
        return array('data'=>$arr,'count'=>$count);
    }
    public function dayTitleCreate($data)
    {
        $obj = ParseObject::create('DayTitle');
        //upload image

        $obj->set('Title', $data['Title']);
        $obj->set("TitleDate", $data['TitleDate']);
        $obj->save();
    }
    public function dayTitleUpdate($data,$id)
    {
        $query = new ParseQuery("DayTitle");



        try {
            //$idobject= $id;
            $obj = $query->get($id);
            $obj->set('Title', $data['Title']);
            $obj->set("TitleDate", $data['TitleDate']);
            $obj->save();

            return true;
        } catch (ParseException $ex) {
            return false;
        }


    }
    public function dayTitleDelete($id)
    {
        $query = new ParseQuery("DayTitle");
        try {
            $obj = $query->get($id);
            $obj->destroy();

            return true;
        } catch (ParseException $ex) {
            return false;
        }
    }



}