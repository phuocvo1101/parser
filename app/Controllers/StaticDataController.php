<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\StaticDataModel;

class StaticDataController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new StaticDataModel();
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

        $Pagination = new Pagination($limit,'index.php?controller=staticData&action=index&search='.$search);//,$base_url

        //$totalPages = $Pagination->totalPages($totalRecord);
        $limit = (int)$Pagination->limit;
        $start = (int)$Pagination->start();

        $result = $this->model->listStaticData($start,$limit,$search);
        $totalRecord=$result['count'];
        // var_dump($totalRecord);die();
        $totalPages = $Pagination->totalPages($totalRecord);

        $listPage= $Pagination->listPages($totalPages);

        $this->template->assign('staticData',$result['data']);
        $this->template->assign('search',$search);
        $this->template->assign('limit',$limit);
        $this->template->assign('totalrecords',$totalRecord);
        $this->template->assign('totalpages',$totalPages);
        $this->template->assign('listPage',$listPage);

        return $this->template->fetch('staticData/index.tpl');


    }
    public function createAction()
    {
      //  echo 'hhhhh';die();
        if(isset($_POST['create'])){
            $title= isset($_POST['Title'])?$_POST['Title']:'';
            $content= isset($_POST['Content'])?$_POST['Content']:'';
            // var_dump($photo);die();
            $data= array(
                'Content'=>$content,
                'Title'=>$title,
            );
            //var_dump($data);die();
            $this->model->staticDataCreate($data);
            return $this->indexAction();
        }
        return $this->template->fetch('staticData/create.tpl');


    }
    public function updateAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['update'])){
            $title= isset($_POST['Title'])?$_POST['Title']:'';
            $content= isset($_POST['Content'])?$_POST['Content']:'';
            // var_dump($photo);die();
            $data= array(
                'Content'=>$content,
                'Title'=>$title,
            );
            // var_dump($data);die();

            $this->model->staticDataUpdate($data,$id);
            return $this->indexAction();
        }

        $static= $this->model->staticDataId($id);
        $this->template->assign('static',$static);
        return $this->template->fetch('staticData/update.tpl');
        // echo 'update test';
    }
    public function deleteAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['delete'])){
            //  echo $id;die();
            $this->model->staticDataDelete($id);
            return $this->indexAction();
        }
        $static= $this->model->staticDataId($id);
        $this->template->assign('static',$static);
        return $this->template->fetch('staticData/delete.tpl');
    }
}
