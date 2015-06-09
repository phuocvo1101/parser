<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\GalleryFolderModel;

class GalleryFolderController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new GalleryFolderModel();
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

        $Pagination = new Pagination($limit,'index.php?controller=galleryFolder&action=index&search='.$search);//,$base_url

        //$totalPages = $Pagination->totalPages($totalRecord);
        $limit = (int)$Pagination->limit;
        $start = (int)$Pagination->start();

        $result = $this->model->listGalleryFolder($start,$limit,$search);
        $totalRecord=$result['count'];
        // var_dump($totalRecord);die();
        $totalPages = $Pagination->totalPages($totalRecord);

        $listPage= $Pagination->listPages($totalPages);

        $this->template->assign('galleryFolder',$result['data']);
        $this->template->assign('search',$search);
        $this->template->assign('limit',$limit);
        $this->template->assign('totalrecords',$totalRecord);
        $this->template->assign('totalpages',$totalPages);
        $this->template->assign('listPage',$listPage);

        return $this->template->fetch('galleryFolder/index.tpl');


    }
    public function createAction()
    {
        if(isset($_POST['create'])){
            $title= isset($_POST['Title'])?$_POST['Title']:'';
            $photo= isset($_FILES['Photo'])?$_FILES['Photo']:'';
            // var_dump($photo);die();
            $data= array(
                'Photo'=>$photo,
                'Title'=>$title,
            );
            //var_dump($data);die();
            $this->model->galleryFolderCreate($data);
            return $this->indexAction();
        }
        return $this->template->fetch('galleryFolder/create.tpl');


    }
    public function updateAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['update'])){
            $title= isset($_POST['Title'])?$_POST['Title']:'';
            $photo= isset($_FILES['Photo'])?$_FILES['Photo']:'';
            // var_dump($photo);die();
            $data= array(
                'Photo'=>$photo,
                'Title'=>$title,
            );
           // var_dump($data);die();

            $this->model->galleryFolderUpdate($data,$id);
            return $this->indexAction();
        }

        $galleryFolder= $this->model->galleryFolderId($id);
        $this->template->assign('galleryFolder',$galleryFolder);
        return $this->template->fetch('galleryFolder/update.tpl');
        // echo 'update test';
    }
    public function deleteAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['delete'])){
            //  echo $id;die();
            $this->model->galleryFolderDelete($id);
            return $this->indexAction();
        }
        $galleryFolder= $this->model->galleryFolderId($id);
        $this->template->assign('galleryFolder',$galleryFolder);
        return $this->template->fetch('galleryFolder/delete.tpl');
    }
}
