<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\ReportModel;

class ReportController extends BaseController implements IBaseController{

    public function __construct()
    {
        parent::__construct();
        $this->model = new ReportModel();
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

        $totalRecord = $this->model->listReport(0,10,1,$search);

        $limit = isset($_REQUEST['limit']) ?  $_REQUEST['limit'] : 10;

        $Pagination = new Pagination($limit,'index.php?controller=report&action=index&search='.$search);//,$base_url

        $totalPages = $Pagination->totalPages($totalRecord);
        $limit = (int)$Pagination->limit;
        $start = (int)$Pagination->start();

        $result = $this->model->listReport($start,$limit,0,$search);

        $listPage= $Pagination->listPages($totalPages);

        $this->template->assign('reports',$result);
        $this->template->assign('search',$search);
        $this->template->assign('limit',$limit);
        $this->template->assign('totalrecords',$totalRecord);
        $this->template->assign('totalpages',$totalPages);
        $this->template->assign('listPage',$listPage);
        return $this->template->fetch('report/index.tpl');
    }
} 