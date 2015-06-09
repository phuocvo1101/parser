<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\AccountModel;

class AccountController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new AccountModel();
    }

    public function indexAction()
    {
        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;


        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;

        $result = $this->model->listAccount($start,$limit,1);

        $Pagination = new Pagination($limit, 'index.php?controller=account&action=index');//,$base_url

        $totalRecord = $result->total;
        $totalPages = $Pagination->totalPages($totalRecord);
        $listPage = $Pagination->listPages($totalPages);
        $accounts = $this->model->listAccount($start,$limit);

        $this->template->assign('limit', $limit);
        $this->template->assign('start', $start);
        $this->template->assign('totalrecords', $totalRecord);
        $this->template->assign('totalpages', $totalPages);
        $this->template->assign('accounts',$accounts);
        $this->template->assign('listPage',$listPage);
        return $this->template->fetch('account/index.tpl');
    }

    public function viewAction()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $result = $this->model->viewAccount($id);
        if($result == false) {
           $this->redirect('account','index');
        }
        $this->template->assign('account',$result);
        return $this->template->fetch('account/view.tpl');
    }

    public function activeAction()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        if(!isset($_REQUEST['status'])) {
            $this->redirect('account','index');
        }
        $status = $_REQUEST['status'];
        if($status==null || ($status!=0 && $status!=1)) {
            $this->redirect('account','index');
        }

        $this->model->activeAccount($id,$status);

        $this->redirect('account','index');

    }

    public function editAction()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

        if(isset($_POST['subFormAccount'])) {
            $message = '';
            $flag = true;
            $name= isset($_POST['account_name']) ? $_POST['account_name'] : '';
            $type = isset($_POST['account_type']) ? $_POST['account_type'] :'';

            if($name=='') {
                $flag= false;
                $message = 'Account name is not blank!';
            }

            if($type=='') {
                $flag= false;
                $message = 'Type has not choosed!';
            }



            if($flag==false) {
                $this->template->assign('message',$message);
                return $this->template->fetch('account/edit.tpl');
            }
            $result = $this->model->updateAccount($id,array(
                'name' => $name,
                'type' => $type,
                'modified_day' => time()
            ));

            if(!$result) {
                $this->template->assign('result',0);
                return $this->template->fetch('account/edit.tpl');
            }

            $this->redirect('account','index');
        }

        $result = $this->model->viewAccount($id);
        if($result == false) {
            $this->redirect('account','index');
        }

        $this->template->assign('account',$result);
        return $this->template->fetch('account/edit.tpl');
    }

    public function deleteAction()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;

        $this->model->deleteAccount($id);

        $this->redirect('account','index');

    }

    public function createAction()
    {

        if(isset($_POST['subFormAccount'])) {
            $message = '';
            $flag = true;
            $name= isset($_POST['account_name']) ? $_POST['account_name'] : '';
            $type = isset($_POST['account_type']) ? $_POST['account_type'] :'';

            if($name=='') {
                $flag= false;
                $message = 'Account name is not blank!';
            }

            if($type=='') {
                $flag= false;
                $message = 'Type has not choosed!';
            }



            if($flag==false) {
                $this->template->assign('message',$message);
                return $this->template->fetch('account/create.tpl');
            }
            $result = $this->model->createAccount(array(
                'name' => $name,
                'type' => $type,
                'status' => 0,
                'modified_day' => time(),
                'created_day' => time()
            ));

            if(!$result) {
                $this->template->assign('result',0);
                return $this->template->fetch('account/create.tpl');
            }

            $this->redirect('account','index');
        }

        return $this->template->fetch('account/create.tpl');
    }
}
