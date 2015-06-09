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
class GalleryFolderModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $app_id = "8lsOjMwbnPUFRjhS13k1XoJFxVZbN6f8vpkzLEJ8";
        $rest_key = "FL5NyJhfPzsVPyQQMNZEXFIJmLQcC3PtkzDuINQ9";
        $master_key = "7QsjpROc3Nb853O6IPceMQJHSk2CBTmnIWBPsbot";

        ParseClient::initialize( $app_id, $rest_key, $master_key );



    }
    public function galleryFolderId($id)
    {
        $query = new ParseQuery("GalleryFolder");
        $query->get($id);
        $result = $query->find();

        $arr= array();
        foreach($result as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'Title'=>$item->get('Title'),
                'Photo'=>$item->get('Photo'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),


            ));

        }
       // echo '<pre>'.print_r($arr,true).'<//pre>' ;die();
        return $arr;
    }
    public function listGalleryFolder($start=0, $limit = 0,$search='')
    {
        $query = new ParseQuery("GalleryFolder");
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
                'Photo'=>$item->get('Photo'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),

            ));

        }
        //  echo '<pre>'.print_r($arr,true).'</pre>';die();

//echo '<pre>'.print_r($arr,true).'</pre>';die();
        return array('data'=>$arr,'count'=>$count);
    }
    public function galleryFolderCreate($data)
    {
        $obj = ParseObject::create('GalleryFolder');
        //upload image
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

        $obj->set('Title', $data['Title']);
        $obj->set("Photo", $file);
        $obj->save();
    }
    public function galleryFolderUpdate($data,$id)
    {
        $query = new ParseQuery("GalleryFolder");
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
        }


        try {
            //$idobject= $id;
            $obj = $query->get($id);
            $obj->set('Title', $data['Title']);
            if($_FILES['Photo']['name'] !=''){
                $obj->set("Photo", $file);
            }
            $obj->save();

            return true;
        } catch (ParseException $ex) {
            return false;
        }


    }
    public function galleryFolderDelete($id)
    {
        $query = new ParseQuery("GalleryFolder");
        try {
            $obj = $query->get($id);
            $obj->destroy();

            return true;
        } catch (ParseException $ex) {
            return false;
        }
    }



}