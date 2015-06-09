<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\InstallationModel;

class InstallationController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new InstallationModel();
    }

    public function indexAction()
    {
        //echo 'jdkdk';die();
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

        $Pagination = new Pagination($limit,'index.php?controller=installation&action=index&search='.$search);//,$base_url

        //$totalPages = $Pagination->totalPages($totalRecord);
        $limit = (int)$Pagination->limit;
        $start = (int)$Pagination->start();

        $result = $this->model->listInstallation($start,$limit,$search);
      //  var_dump($result);die();
        $totalRecord=$result['count'];
        $data=$result['data'];
        // var_dump($totalRecord);die();
        $totalPages = $Pagination->totalPages($totalRecord);

        $listPage= $Pagination->listPages($totalPages);

        $this->template->assign('install',$data);
        $this->template->assign('search',$search);
        $this->template->assign('limit',$limit);
        $this->template->assign('totalrecords',$totalRecord);
        $this->template->assign('totalpages',$totalPages);
        $this->template->assign('listPage',$listPage);

        return $this->template->fetch('installation/index.tpl');


    }
    public function deleteAction()
    {
        $id= $_GET['id'];
        $this->model->installationDelete($id);
        return $this->indexAction();
    }
}
