<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\TestModel;

class TestController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new TestModel();
    }

    public function indexAction()
    {
       // $tests= $this->model->listTest();
       // echo '<pre>'.print_r($tests,true).'</pre>';die();

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

        $Pagination = new Pagination($limit,'index.php?controller=test&action=index&search='.$search);//,$base_url

        //$totalPages = $Pagination->totalPages($totalRecord);
        $limit = (int)$Pagination->limit;
        $start = (int)$Pagination->start();

        $result = $this->model->listTest($start,$limit,$search);
        $totalRecord=$result['count'];
       // var_dump($totalRecord);die();
        $totalPages = $Pagination->totalPages($totalRecord);

        $listPage= $Pagination->listPages($totalPages);

        $this->template->assign('tests',$result['data']);
        $this->template->assign('search',$search);
        $this->template->assign('limit',$limit);
        $this->template->assign('totalrecords',$totalRecord);
        $this->template->assign('totalpages',$totalPages);
        $this->template->assign('listPage',$listPage);

        return $this->template->fetch('test/index.tpl');


    }
    public function createAction()
    {
        if(isset($_POST['create'])){
            $mode= isset($_POST['mode'])?$_POST['mode']:'';
            $name= isset($_POST['name'])?$_POST['name']:'';
            $phone= isset($_POST['phone'])?$_POST['phone']:'';
            $score= isset($_POST['score'])?$_POST['score']:'';
            $data= array(
                'mode'=>$mode,
                'name'=>$name,
                'phone'=>$phone,
                'score'=>(int)$score
            );
            $this->model->testCreate($data);
            return $this->indexAction();
        }
        return $this->template->fetch('test/create.tpl');


    }
    public function updateAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['update'])){
            $mode= isset($_POST['mode'])?$_POST['mode']:'';
            $name= isset($_POST['name'])?$_POST['name']:'';
            $phone= isset($_POST['phone'])?$_POST['phone']:'';
            $score= isset($_POST['score'])?$_POST['score']:'';
            $data= array(
                'mode'=>$mode,
                'name'=>$name,
                'phone'=>$phone,
                'score'=>(int)$score
            );
            $this->model->testUpdate($data,$id);
            return $this->indexAction();
        }

        $test= $this->model->testId($id);
        $this->template->assign('test',$test);
        return $this->template->fetch('test/update.tpl');
       // echo 'update test';
    }
    public function deleteAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['delete'])){
          //  echo $id;die();
            $this->model->testDelete($id);
            return $this->indexAction();
        }
        $test= $this->model->testId($id);
        $this->template->assign('test',$test);
        return $this->template->fetch('test/delete.tpl');
    }
}
