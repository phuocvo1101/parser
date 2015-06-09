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
class InstallationModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $app_id = "8lsOjMwbnPUFRjhS13k1XoJFxVZbN6f8vpkzLEJ8";
        $rest_key = "FL5NyJhfPzsVPyQQMNZEXFIJmLQcC3PtkzDuINQ9";
        $master_key = "7QsjpROc3Nb853O6IPceMQJHSk2CBTmnIWBPsbot";

        ParseClient::initialize( $app_id, $rest_key, $master_key );



    }
    public function installationId($id)
    {
        $query= ParseInstallation::query();
        $query->get($id);
       // $query->equalTo("objectId", $id);
        $result = $query->find(true);
        // return $result;
        $arr= array();
        foreach($result as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'appIdentifier'=>$item->get('appIdentifier'),
                'appName'=>$item->get('appName'),
                'appVersion'=>$item->get('appVersion'),
                'deviceName'=>$item->get('deviceName'),
                'deviceToken'=>$item->get('deviceToken'),
                'deviceTokenLastModified'=>$item->get('deviceTokenLastModified'),
                'deviceType'=>$item->get('deviceType'),
                'installationId'=>$item->get('installationId'),
                'parseVersion'=>$item->get('parseVersion'),
                'pushType'=>$item->get('pushType'),
                'timeZone'=>$item->get('timeZone'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),

            ));

        }
        return $arr;
    }
    public function listInstallation($start=0, $limit = 0,$search='')
    {


        //$query = new ParseQuery("Installation");
        $query= ParseInstallation::query();

   //     $result = $query->find(true);
     //  echo '<pre>'.print_r($result,true).'</pre>';die();
        if($start ==0) {
            $skip = $start;
        } else{
            $skip = $start - 1;
        }
        if($search==''){
            $count = $query->count(true);
        }else{
            $query->equalTo("appName", $search); // conditional
            $count = $query->count(true);

        }

        $query->skip($skip);
        if($limit !=0){
            $query->limit($limit); // default 100, max 1000
        }
        if($search!=''){
            $query->equalTo("appName", $search);
        }

// All results:
        $query->descending("updateAt");
        $query->descending("createdAt");
        $results = $query->find(true);
      //  echo '<pre>'.print_r($results,true).'</pre>';die();
        // return $results;//
        $arr= array();
        foreach($results as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'appIdentifier'=>$item->get('appIdentifier'),
                'appName'=>$item->get('appName'),
                'appVersion'=>$item->get('appVersion'),
                'deviceName'=>$item->get('deviceName'),
                'deviceToken'=>$item->get('deviceToken'),
                'deviceTokenLastModified'=>$item->get('deviceTokenLastModified'),
                'deviceType'=>$item->get('deviceType'),
                'installationId'=>$item->get('installationId'),
                'parseVersion'=>$item->get('parseVersion'),
                'pushType'=>$item->get('pushType'),
                'timeZone'=>$item->get('timeZone'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),

            ));

        }
        //  echo '<pre>'.print_r($arr,true).'</pre>';die();

//echo '<pre>'.print_r($arr,true).'</pre>';die();
        return array('data'=>$arr,'count'=>$count);
    }

    public function installationUpdate($data,$id)
    {
       $query= ParseInstallation::query();
        //$query = ParseObject::create('Installation');
        try {
            //$idobject= $id;

            $obj = $query->get($id);
            $obj->set('appIdentifier', $data['appIdentifier']);
            $obj->set('timeZone', $data['timeZone']);
            $obj->set('deviceName', $data['deviceName']);
            $obj->set('appName', $data['appName']);
            $obj->set('appVersion', $data['appVersion']);
            $obj->set('parseVersion', $data['parseVersion']);
            $obj->set('deviceTokenLastModified', $data['deviceTokenLastModified']);

            $obj->save(true);

            return true;
        } catch (ParseException $ex) {
            echo 'jjj';die();
            return false;
        }


    }

    public function installationDelete($id)
    {
        //$query = new ParseQuery("test");
        $query= ParseInstallation::query();
        try {
            $obj = $query->get($id);
            $obj->destroy(true);


            return true;
        } catch (ParseException $ex) {
            return false;
        }
    }



}