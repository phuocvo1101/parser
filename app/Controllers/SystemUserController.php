<?php
/**
 * Created by PhpStorm.
 * User: Tam Tran
 * Date: 6/7/2015
 * Time: 11:22 PM
 */

namespace Controllers;


use Models\SystemUserModel;
use Libraries\Pagination;
use Models\AccountModel;

class SystemUserController extends  BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new SystemUserModel();
    }

    public function indexAction()
    {
        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;


        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
        $account_id = isset($_REQUEST['account_id']) ? $_REQUEST['account_id'] : null;
        if(isset($_SESSION['type']) && $_SESSION['type']!='admin') {
            $account_id = $_SESSION['account_id'];
        }
        $result = $this->model->listUser($start,$limit,1,$account_id);

        $pagination = new Pagination($limit, 'index.php?controller=systemuser&action=index');//,$base_url

        $totalRecord = $result->total;
        $totalPages = $pagination->totalPages($totalRecord);
        $listPage = $pagination->listPages($totalPages);
        $users = $this->model->listUser($start,$limit,0,$account_id);

        $this->template->assign('limit', $limit);
        $this->template->assign('start', $start);
        $this->template->assign('totalrecords', $totalRecord);
        $this->template->assign('totalpages', $totalPages);
        $this->template->assign('users',$users);
        $this->template->assign('listPage',$listPage);
        return $this->template->fetch('systemuser/index.tpl');
    }

    public function viewAction()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $result = $this->model->viewUser($id);
        if($result == false) {
            $this->redirect('systemuser','index');
        }
        $this->template->assign('systemuser',$result);
        return $this->template->fetch('systemuser/view.tpl');
    }

    public function activeAction()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        if(!isset($_REQUEST['status'])) {
            $this->redirect('systemuser','index');
        }
        $status = $_REQUEST['status'];
        if($status==null || ($status!=0 && $status!=1)) {
            $this->redirect('systemuser','index');
        }

        $this->model->activeUser($id,$status);

        $this->redirect('systemuser','index');

    }

    public function editAction()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

        if(isset($_POST['subFormUser'])) {
            $message = '';
            $flag = true;
            $email= isset($_POST['email']) ? $_POST['email'] : '';
            $fullname = isset($_POST['fullname']) ? $_POST['fullname'] :'';

            if($email=='') {
                $flag= false;
                $message = 'Email should not be blank!';
            }

            if($fullname=='') {
                $flag= false;
                $message = 'Full Name should not be blank!';
            }



            if($flag==false) {
                $this->template->assign('message',$message);
                return $this->template->fetch('systemuser/edit.tpl');
            }
            $result = $this->model->updateUser($id,array(
                'email' => $email,
                'fullname' => $fullname
            ));

            if(!$result) {
                $this->template->assign('result',0);
                return $this->template->fetch('systemuser/edit.tpl');
            }

            $this->redirect('systemuser','index');
        }

        $result = $this->model->viewUser($id);
        if($result == false) {
            $this->redirect('systemuser','index');
        }

        $this->template->assign('user',$result);
        return $this->template->fetch('systemuser/edit.tpl');
    }

    public function deleteAction()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

        $this->model->deleteUser($id);

        $this->redirect('user','index');

    }

    public function createAction()
    {
        $accountModel = new AccountModel();

        $account_id = isset($_REQUEST['account_id']) ? $_REQUEST['account_id'] : null;
        if(isset($_SESSION['type']) && $_SESSION['type']!='admin') {
            $account_id = $_SESSION['account_id'];
        }

        $listAccount = $accountModel->getAllAccount($account_id);
        $this->template->assign('listAccount',$listAccount);
        if(isset($_POST['subFormUser'])) {
            $message = '';
            $flag = true;
            $userJson = json_encode($_POST);
            $user = json_decode($userJson);

            $this->template->assign('user',$user);

            if(!isset($_POST['username']) || $_POST['username']=='') {
                $flag= false;
                $message = 'UserName should not be blank!';
            }

            if(!isset($_POST['password']) || $_POST['password']=='') {
                $flag= false;
                $message = 'Password should not be blank!';
            }

            if(!isset($_POST['confirm_password']) || (isset($_POST['password']) && $_POST['confirm_password']!=$_POST['password'])) {
                $flag= false;
                $message = 'Confirm-Password does not match Password!';
            }

            if(!isset($_POST['email']) || $_POST['email']=='') {
                $flag= false;
                $message = 'Email should not be blank!';
            }

            if(!isset($_POST['fullname']) || $_POST['fullname']=='') {
                $flag= false;
                $message = 'Full Name should not be blank!';
            }

            if(!isset($_POST['account_id']) || $_POST['account_id']=='') {
                $flag= false;
                $message = 'Account has not choosed!';
            }

            if($this->model->checkExistUserName($_POST['username'])) {
                $flag= false;
                $message = 'UserName is already used!';
            }


            if($flag==false) {
                $this->template->assign('message',$message);
                return $this->template->fetch('systemuser/create.tpl');
            }
            $result = $this->model->createUser(array(
                'username' => $_POST['username'],
                'status' => 0,
                'password' => md5($_POST['password']),
                'email' => $_POST['email'],
                'fullname' => $_POST['fullname'],
                'account_id' => $_POST['account_id'],
            ));

            if(!$result) {
                $this->template->assign('result',0);
                return $this->template->fetch('systemuser/create.tpl');
            }

            $this->redirect('systemuser','index');
        }

        return $this->template->fetch('systemuser/create.tpl');
    }

    public function changePasswordAction()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $result = $this->model->viewUser($id);
        if($result == false) {
            $this->redirect('user','index');
        }
        $this->template->assign('user',$result);
        if(isset($_POST['subFormUser'])) {
            $message = '';
            $flag = true;
            $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
            $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            if(!isset($_POST['current_password']) || $_POST['current_password']=='') {
                $flag= false;
                $message = 'Current Password should not blank!';
            }

            if(!isset($_POST['password']) || $_POST['password']=='') {
                $flag= false;
                $message = 'New Password should not be blank!';
            }

            if(!isset($_POST['confirm_password']) || (isset($_POST['password']) && $_POST['confirm_password']!=$_POST['password'])) {
                $flag= false;
                $message = 'Confirm-Password does not match New Password!';
            }

            if(!$this->model->checkPassword($id, md5($currentPassword))) {
                $flag= false;
                $message = 'Current Password is not invalid!';
            }

            if($flag==false) {
                $this->template->assign('message',$message);
                return $this->template->fetch('systemuser/changepassword.tpl');
            }
            $result = $this->model->updateUser($id,array(
                'password' => md5($confirmPassword)
            ));

            if(!$result) {
                $this->template->assign('result',0);
                return $this->template->fetch('systemuser/changepassword.tpl');
            }
            $this->redirect('systemuser','index');
        }

        return $this->template->fetch('systemuser/changepassword.tpl');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('systemuser','login');
    }

    public function login()
    {
        if(isset($_POST['submitSigin'])) {
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $result = $this->model->checkLogin(array(
                'username' => $username,
                'password' => md5($password)
            ));
            if($result==false || !isset($result->id)) {
                $message = 'Username or Password is invalid!';
                $this->template->assign('message',$message);
                return $this->template->fetch('systemuser/login.tpl');
            }

            $_SESSION['username'] = $result->username;
            $_SESSION['fullname'] = $result->fullname;
            $_SESSION['email'] = $result->email;
            $_SESSION['user_id'] = $result->id;
            $_SESSION['type'] = $result->type;
            $_SESSION['account_id'] = $result->account_id;
            $this->redirect();
        }
        return $this->template->fetch('systemuser/login.tpl');
    }

    public function changePasswordUserAction()
    {
        if(isset($_POST['subFormChangePassword'])) {
            $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
            $newPassword =  isset($_POST['new_password']) ? $_POST['new_password'] : '';
            $confirmPassword =  isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            if(empty($currentPassword)) {
                $message = 'Current Password is not blank!';
                $this->template->assign('message',$message);
                return $this->template->fetch('systemuser/changepassworduser.tpl');
            }

            if(empty($newPassword)) {
                $message = 'New Password is not blank!';
                $this->template->assign('message',$message);
                return $this->template->fetch('systemuser/changepassworduser.tpl');
            }

            if($newPassword!=$confirmPassword) {
                $message = 'New Password and Confirm Passowrd is not same!';
                $this->template->assign('message',$message);
                return $this->template->fetch('user/changepassworduser.tpl');
            }
            $checkPassword = $this->model->checkLogin(array(
                'username' => $_SESSION['username'],
                'password' => md5($currentPassword)
            ));

            if($checkPassword==false) {
                $message = 'Current Password is invalid!';
                $this->template->assign('message',$message);
                return $this->template->fetch('systemuser/changepassworduser.tpl');
            }

            $result = $this->model->updatePassword(array(
                'user_id' => $_SESSION['user_id'],
                'password' => md5($confirmPassword)
            ));

            if(!$result) {
                $this->template->assign('result',0);
                return $this->template->fetch('systemuser/changepassworduser.tpl');
            }
            session_destroy();
            header('location:index.php');

        }
        return $this->template->fetch('systemuser/changepassworduser.tpl');
    }
} 