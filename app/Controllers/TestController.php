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
       /* $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;


        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
        $page = ($start/$limit) + 1;

        $result = $this->model->listProduct($page, $limit);
        $Pagination = new Pagination($limit, 'index.php?controller=product&action=index&search=' . $search);//,$base_url

        $totalRecord = $result['total'];
        $totalPages = $Pagination->totalPages($totalRecord);
        $listPage = $Pagination->listPages($totalPages);*/

       // $this->template->assign('tests', $tests);
       /* $this->template->assign('search', $search);
        $this->template->assign('limit', $limit);
        $this->template->assign('start', $start);
        $this->template->assign('totalrecords', $totalRecord);
        $this->template->assign('totalpages', $totalPages);
        $this->template->assign('listPage', $listPage);*/

    }
}
