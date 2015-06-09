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
class GalleryModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $app_id = "8lsOjMwbnPUFRjhS13k1XoJFxVZbN6f8vpkzLEJ8";
        $rest_key = "FL5NyJhfPzsVPyQQMNZEXFIJmLQcC3PtkzDuINQ9";
        $master_key = "7QsjpROc3Nb853O6IPceMQJHSk2CBTmnIWBPsbot";

        ParseClient::initialize( $app_id, $rest_key, $master_key );



    }
    public function galleryId($id)
    {
        $query = new ParseQuery("Gallery");
        $query->get($id);
        $result = $query->find();

        $arr= array();
        foreach($result as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'FolderId'=>$item->get('FolderId'),
                'Photo'=>$item->get('Photo'),
                'Title'=>$item->get('Title'),
                'Type'=>$item->get('Type'),
                'Video'=>$item->get('Video'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),


            ));

        }
        // echo '<pre>'.print_r($arr,true).'<//pre>' ;die();
        return $arr;

        ///
    }
    public function listGalleryFolder()
    {
        $query = new ParseQuery("GalleryFolder");
        $results = $query->find();
        $arr= array();
        foreach($results as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'Title'=>$item->get('Title')

            ));

        }
        return $arr;
    }
    public function listGallery($start=0, $limit = 0,$search='')
    {
        $query = new ParseQuery("Gallery");
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

        if($search!=''){
            $query->equalTo("Title", $search);
        }

// All results:
        $query->descending("updateAt");
       $query->descending("createdAt");
        $results = $query->find();
       // var_dump($results);die();
      //  echo '<pre>'.print_r($results,true).'</pre>';die();
         //return $results;
        $arr= array();
        foreach($results as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'FolderId'=>$item->get('FolderId'),
                'Photo'=>$item->get('Photo'),
                'Title'=>$item->get('Title'),
                'Type'=>$item->get('Type'),
                'Video'=>$item->get('Video'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),

            ));

        }

        //echo '<pre>'.print_r($arr[0]['Photo'],true).'</pre>';die();
        return array('data'=>$arr,'count'=>$count);
    }
    public function testParseFileUpload()
    {
        $file = ParseFile::createFromData("Fosco", "test.txt");
        $file->save();
        $this->assertTrue(
            strpos($file->getURL(), 'http') !== false
        );
        $this->assertNotEquals("test.txt", $file->getName());
    }
    public function galleryCreate($data)
    {
        $obj = ParseObject::create('Gallery');
        //$obj->set('Photo', $data['Photo']);
        //$obj->set('Video', $data['Video']);
        //image
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

        //video
        if($_FILES['Video']['name']!=''){
            $info1 = pathinfo($_FILES['Video']['name']);
            $ext1 = $info1['extension']; // get the extension of the file
            $newname1 = "video.".$ext1;
            $dir1 = realpath(dirname(__FILE__)).'/../../public/upload';
            $target1 = $dir1.'/videos/'.$newname1;
            move_uploaded_file( $_FILES['Video']['tmp_name'], $target1);

            $localFilePath1 = $target1;
            $name1 = $info1['basename'];
            $file1 = ParseFile::createFromFile($localFilePath1, $name1);
            $file1->save();
            $obj->set("Video", $file1);
        }

        $obj->set('FolderId', $data['FolderId']);
        $obj->set('Title', $data['Title']);
        $obj->set('Type', $data['Type']);
        $obj->save();

    }
    public function galleryUpdate($data,$id)
    {
        $query = new ParseQuery("Gallery");
        try {
            //$idobject= $id;
            $obj = $query->get($id);
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

            //video
            if($_FILES['Video']['name']!=''){
                $info1 = pathinfo($_FILES['Video']['name']);
                $ext1 = $info1['extension']; // get the extension of the file
                $newname1 = "video.".$ext1;
                $dir1 = realpath(dirname(__FILE__)).'/../../public/upload';
                $target1 = $dir1.'/videos/'.$newname1;
                move_uploaded_file( $_FILES['Video']['tmp_name'], $target1);

                $localFilePath1 = $target1;
                $name1 = $info1['basename'];
                $file1 = ParseFile::createFromFile($localFilePath1, $name1);
                $file1->save();
                $obj->set("Video", $file1);
            }

            $obj->set('FolderId', $data['FolderId']);
            $obj->set('Title', $data['Title']);
            $obj->set('Type', $data['Type']);
            $obj->save();


            return true;
        } catch (ParseException $ex) {
            return false;
        }


    }
    public function galleryDelete($id)
    {
        $query = new ParseQuery("Gallery");
        try {
            $obj = $query->get($id);
            $obj->destroy();

            return true;
        } catch (ParseException $ex) {
            return false;
        }
    }



}