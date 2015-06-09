<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\UserModel;

class UserController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }

    public function indexAction()
    {
        if(isset($_REQUEST['search'])){
            $search=$_REQUEST['search'];
        }else{
            $search='';
        }
        if (isset($_POST['go'])) {
            $search = $_POST['search'] ? $_POST['search'] : '';
        }
        // echo $search;die();
        //$totalRecord = count($this->model->listTest(0,0,$search));
        // $result = $this->model->listTest($start,$limit,$search);
        // var_dump($totalRecord);die();
        $limit = isset($_REQUEST['limit']) ?  $_REQUEST['limit'] : 10;

        $Pagination = new Pagination($limit,'index.php?controller=user&action=index&search='.$search);//,$base_url

        //$totalPages = $Pagination->totalPages($totalRecord);
        $limit = (int)$Pagination->limit;
        $start = (int)$Pagination->start();

        $result = $this->model->listUser($start,$limit,$search);
        $totalRecord=$result['count'];
        $users=$result['data'];
       // var_dump($users);die();
        $totalPages = $Pagination->totalPages($totalRecord);

        $listPage= $Pagination->listPages($totalPages);
//echo '<pre>'.print_r($result['data'],true).'</pre>';die();
        $this->template->assign('users',$users);
        $this->template->assign('search',$search);
        $this->template->assign('limit',$limit);
        $this->template->assign('totalrecords',$totalRecord);
        $this->template->assign('totalpages',$totalPages);
        $this->template->assign('listPage',$listPage);

        return $this->template->fetch('user/index.tpl');


    }
    public function createAction()
    {
        //  echo 'hhhhh';die();
        if(isset($_POST['create'])){
            $username= isset($_POST['username'])?$_POST['username']:'';
            $password= isset($_POST['password'])?$_POST['password']:'';
            $repassword= isset($_POST['repassword'])?$_POST['repassword']:'';
            $email= isset($_POST['email'])?$_POST['email']:'';
            $err=array();
            if($password==''){
                $err[]='password is not empty';
            }
            if($username==''){
                $err[]='username is not empty';
            }
            if($email==''){
                $err[]='email is not empty';
            }
            if($repassword==''){
                $err[]='rePassword is not empty';
            }
            if($password != $repassword){
                $err[]='Password is not same rePassword';
            }
            if(count($err)>0){


                $this->template->assign('mss',$err);
                return $this->template->fetch('user/create.tpl');
            }
            // var_dump($photo);die();
            $data= array(
                'email'=>$email,
                'username'=>$username,
                'password'=> $password
            );
            //var_dump($data);die();
            $this->model->userCreate($data);
            return $this->indexAction();
        }
        return $this->template->fetch('user/create.tpl');


    }
    public function updateAction()
    {
       // echo 'ggg';die();
        $id= $_GET['id'];

        $user= $this->model->userId($id);
        foreach($user as $item){
           $email= $item['email'];
        }

        $this->model->userUpdate($email);
        return $this->indexAction();

    }
    public function deleteAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['delete'])){
            //  echo $id;die();
            $this->model->userDelete($id);
            return $this->indexAction();
        }
        $user= $this->model->userId($id);
        $this->template->assign('user',$user);
        return $this->template->fetch('user/delete.tpl');
    }
}
