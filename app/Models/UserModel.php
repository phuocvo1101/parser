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
class UserModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $app_id = "8lsOjMwbnPUFRjhS13k1XoJFxVZbN6f8vpkzLEJ8";
        $rest_key = "FL5NyJhfPzsVPyQQMNZEXFIJmLQcC3PtkzDuINQ9";
        $master_key = "7QsjpROc3Nb853O6IPceMQJHSk2CBTmnIWBPsbot";

        ParseClient::initialize( $app_id, $rest_key, $master_key );



    }
    public function userId($id)
    {
        $query = ParseUser::query();
        //$query->equalTo("gender", "female");
        $query->get($id);
        $result = $query->find();

        $arr= array();
        foreach($result as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'username'=>$item->get('username'),
                'email'=>$item->get('email'),
                'password'=>$item->get('password'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),

            ));


        }
       // echo '<pre>'.print_r($arr,true).'<//pre>' ;die();
        return $arr;
    }
    public function listUser($start=0, $limit = 0,$search='')
    {
        $query = ParseUser::query();

        if($start ==0) {
            $skip = $start;
        } else{
            $skip = $start - 1;
        }
        if($search==''){
            $count = $query->count(true);
        }else{
            $query->equalTo("username", $search); // conditional
            $count = $query->count(true);

        }

        $query->skip($skip);
        if($limit !=0){
            $query->limit($limit); // default 100, max 1000
        }
        // $query->addDescending('updateAt')->addDescending('createAt');
        if($search!=''){
            $query->equalTo("username", $search);
        }

// All results:
        $query->descending("updateAt");
        $query->descending("createdAt");
        $results = $query->find(true);
      //  echo '<pre>'.print_r($results,true).'</pre>';die();
        // return $results;
        $arr= array();
        foreach($results as $item) {
            $arr[]= (array(
                'objectId'=>$item->getObjectId(),
                'username'=>$item->get('username'),
                'email'=>$item->get('email'),
                'password'=>$item->get('password'),
                'createAt'=>$item->getCreatedAt()->format('m-d-y H:i:s'),
                'updateAt'=>$item->getUpdatedAt()->format('m-d-y H:i:s'),

            ));

        }
         // echo '<pre>'.print_r($count,true).'</pre>';die();

//echo '<pre>'.print_r($arr,true).'</pre>';die();
        return array('data'=>$arr,'count'=>$count);
    }
    public function userCreate($data)
    {
       // $obj = ParseUser::query();
        $user = new ParseUser();
        $user->set('username', $data['username']);
        $user->set('password', $data['password']);
        $user->set('email', $data['email']);
        try {
            $user->signUp();
            // Hooray! Let them use the app now.
        } catch (ParseException $ex) {
            // Show the error message somewhere and let the user try again.
            echo "Error: " . $ex->getCode() . " " . $ex->getMessage();
        }
    }
    public function userUpdate($email)
    {
        try {
            ParseUser::requestPasswordReset($email);
            // Password reset request was sent successfully
        } catch (ParseException $ex) {
            // Password reset failed, check the exception message
        }
    }
    public function userDelete($id)
    {
        $user= ParseUser::query();
        try {
            $obj = $user->get($id);
            $obj->destroy(true);

            return true;
        } catch (ParseException $ex) {
            return false;
        }
    }



}