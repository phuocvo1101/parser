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
class EventModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $app_id = "8lsOjMwbnPUFRjhS13k1XoJFxVZbN6f8vpkzLEJ8";
        $rest_key = "FL5NyJhfPzsVPyQQMNZEXFIJmLQcC3PtkzDuINQ9";
        $master_key = "7QsjpROc3Nb853O6IPceMQJHSk2CBTmnIWBPsbot";

        ParseClient::initialize( $app_id, $rest_key, $master_key );



    }
    public function eventId($id)
    {
        $query = new ParseQuery("Events");
        $query->get($id);
        $result = $query->find();

        $arr= array();
        foreach($result as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'Title'=>$item->get('Title'),
                'Photo'=>$item->get('Photo'),
                'Content'=>$item->get('Content'),
                'EventDate'=>$item->get('EventDate'),
                'EventYear'=>$item->get('EventYear'),
                'HEvents'=>$item->get('HEvents'),
                'Important'=>$item->get('Important'),
                'Memos'=>$item->get('Memos'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),


            ));

        }
        // echo '<pre>'.print_r($arr,true).'<//pre>' ;die();
        return $arr;
    }
    public function listEvent($start=0, $limit = 0,$search='')
    {
        $query = new ParseQuery("Events");
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
       // echo '<pre>'.print_r($results,true).'</pre>';die();
        $arr= array();
        foreach($results as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'Title'=>$item->get('Title'),
                'Photo'=>$item->get('Photo'),
                'Content'=>$item->get('Content'),
                'EventDate'=>$item->get('EventDate'),
                'EventYear'=>$item->get('EventYear'),
                'HEvents'=>$item->get('HEvents'),
                'Important'=>$item->get('Important'),
                'Memos'=>$item->get('Memos'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),

            ));

        }
        //  echo '<pre>'.print_r($arr,true).'</pre>';die();

//echo '<pre>'.print_r($arr,true).'</pre>';die();
        return array('data'=>$arr,'count'=>$count);
    }
    public function eventCreate($data)
    {
        $obj = ParseObject::create('Events');
        //upload image
        if($_FILES['Photo']['name']!=''){
            $info = pathinfo($_FILES['Photo']['name']);
            $ext = $info['extension']; // get the extension of the file
            $newname = "image.".$ext;
            $dir = realpath(dirname(__FILE__)).'/../../public/upload';
            $target = $dir.'/images/'.$newname;
            move_uploaded_file( $_FILES['Photo']['tmp_name'], $target);

            $localFilePath = $target;
            $name = $info['basename'];
            $file = ParseFile::createFromFile($localFilePath, $name);
            $file->save();
            $obj->set("Photo", $file);
        }

        $obj->set('Title', $data['Title']);
        $obj->set('Content', $data['Content']);
        $obj->set('EventDate', $data['EventDate']);
        $obj->set('EventYear', $data['EventYear']);
        $obj->set('HEvents', $data['HEvents']);
        $obj->set('Important', $data['Important']);
        $obj->set('Memos', $data['Memos']);

        $obj->save();
    }
    public function eventUpdate($data,$id)
    {
        $query = new ParseQuery("Events");



        try {

            //$idobject= $id;
            $obj = $query->get($id);
            if($_FILES['Photo']['name'] !=''){
                $info = pathinfo($_FILES['Photo']['name']);
                $ext = $info['extension']; // get the extension of the file
                $newname = "image.".$ext;
                $dir = realpath(dirname(__FILE__)).'/../../public/upload';
                $target = $dir.'/images/'.$newname;
                move_uploaded_file( $_FILES['Photo']['tmp_name'], $target);

                $localFilePath = $target;
                $name = $info['basename'];
                $file = ParseFile::createFromFile($localFilePath, $name);
                $file->save();
                $obj->set("Photo", $file);
            }
            $obj->set('Title', $data['Title']);
            $obj->set('Content', $data['Content']);
            $obj->set('EventDate', $data['EventDate']);
            $obj->set('EventYear', $data['EventYear']);
            $obj->set('HEvents', $data['HEvents']);
            $obj->set('Important', $data['Important']);
            $obj->set('Memos', $data['Memos']);

            $obj->save();
            return true;
        } catch (ParseException $ex) {
            return false;
        }


    }
    public function eventDelete($id)
    {
        $query = new ParseQuery("Events");
        try {
            $obj = $query->get($id);
            $obj->destroy();

            return true;
        } catch (ParseException $ex) {
            return false;
        }
    }



}