<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\PushModel;

class PushController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new PushModel();
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

        $totalRecord = $this->model->getdataMessage(0,10,1,$search);

        $limit = isset($_REQUEST['limit']) ?  $_REQUEST['limit'] : 10;

        $Pagination = new Pagination($limit,'index.php?controller=push&action=index&search='.$search);//,$base_url

        $totalPages = $Pagination->totalPages($totalRecord);
        $limit = (int)$Pagination->limit;
        $start = (int)$Pagination->start();

        $result = $this->model->getdataMessage($start,$limit,0,$search);
       // echo '<pre>'.print_r($result,true).'</pre>';die();

        $listPage= $Pagination->listPages($totalPages);

        $this->template->assign('message',$result);
        $this->template->assign('search',$search);
        $this->template->assign('limit',$limit);
        $this->template->assign('totalrecords',$totalRecord);
        $this->template->assign('totalpages',$totalPages);
        $this->template->assign('listPage',$listPage);
        return $this->template->fetch('push/index.tpl');

    }
    public function sendMessage()
    {
        if(isset($_POST['sendmess'])){
            $channel= isset($_POST['chan'])?$_POST['chan']:'';
            $mess= isset($_POST['message'])?$_POST['message']:'';
            if($channel==''){
                $target= 'everyone';
            }else{
                $target=$channel;
            }
            $result= $this->model->PushToMessage($mess,$channel);
            if($result==true){
                $status=1;
            }else{
                $status=0;
            }
            $data= array(
                'mess'=>$mess,
                'status'=>$status,
                'target'=>$target,
                'name'=>$mess,
                'time'=> time()
            );
            $this->model->insertMessage($data);
            return $this->indexAction();
        }
        return $this->template->fetch('push/send.tpl');
    }
    public function resendMessage()
    {
            $id= $_GET['id'];
            $dataMess= $this->model->getMessage($id);

            $target= $dataMess->target;
            if($target=='everyone'){
                $channel='';
            }else{
                $channel=$target;
            }
            $mess= $dataMess->name;
       // echo $channel.'-'.$mess;die();
            $result= $this->model->PushToMessage($mess,$channel);
            return $this->indexAction();
    }
    public function updateMessage()
    {
        if(isset($_POST['sendmess'])){
            $channel= isset($_POST['chan'])?$_POST['chan']:'';
            $mess= isset($_POST['message'])?$_POST['message']:'';
            if($channel==''){
                $target= 'everyone';
            }else{
                $target=$channel;
            }
            $result= $this->model->PushToMessage($mess,$channel);
            if($result==true){
                $status=1;
            }else{
                $status=0;
            }
            $data= array(
                'mess'=>$mess,
                'status'=>$status,
                'target'=>$target,
                'name'=>$mess,
                'time'=> time()
            );
            $this->model->insertMessage($data);
            return $this->indexAction();
        }
        $id= $_GET['id'];
        $dataMess= $this->model->getMessage($id);
        $this->template->assign('data',$dataMess);

        return $this->template->fetch('push/send.tpl');
    }
}
