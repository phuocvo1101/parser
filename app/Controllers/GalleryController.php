<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\GalleryModel;

class GalleryController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new GalleryModel();
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

        $Pagination = new Pagination($limit,'index.php?controller=gallery&action=index&search='.$search);//,$base_url

        //$totalPages = $Pagination->totalPages($totalRecord);
        $limit = (int)$Pagination->limit;
        $start = (int)$Pagination->start();

        $result = $this->model->listGallery($start,$limit,$search);
        $totalRecord=$result['count'];
        // var_dump($totalRecord);die();
        $totalPages = $Pagination->totalPages($totalRecord);

        $listPage= $Pagination->listPages($totalPages);
        //var_dump($result['data']);die();
        $this->template->assign('gallery',$result['data']);
        $this->template->assign('search',$search);
        $this->template->assign('limit',$limit);
        $this->template->assign('totalrecords',$totalRecord);
        $this->template->assign('totalpages',$totalPages);
        $this->template->assign('listPage',$listPage);

        return $this->template->fetch('gallery/index.tpl');


    }
    public function createAction()
    {
        if(isset($_POST['create'])){
            $FolderId= isset($_POST['FolderId'])?$_POST['FolderId']:'';
            $title= isset($_POST['Title'])?$_POST['Title']:'';
            $type= isset($_POST['Type'])?$_POST['Type']:'';
            $photo= isset($_FILES['Photo'])?$_FILES['Photo']:'';
            $video= isset($_FILES['Video'])?$_FILES['Video']:'';
           // var_dump($_FILES['Video']['size']);die();
            if($_FILES['Video']['size']> 9*1024*1024){
                $mess= "file exceeds filesystem size limit";

                $galleryFolder=$this->model->listGalleryFolder();
                $this->template->assign('mess',$mess);
                $this->template->assign('folder',$galleryFolder);
                // echo '<pre>'.print_r($galleryFolder,true).'</pre>';die();
                return $this->template->fetch('gallery/create.tpl');
            }
            $data= array(
                'FolderId'=>$FolderId,
                'Photo'=>$photo,
                'Video'=>$video,
                'Title'=>$title,
                'Type'=>(int)$type,
            );
           // echo '<pre>'.print_r($data,true).'</pre>';die();
            $this->model->galleryCreate($data);
            return $this->indexAction();
        }
        $galleryFolder=$this->model->listGalleryFolder();
        $this->template->assign('folder',$galleryFolder);
       // echo '<pre>'.print_r($galleryFolder,true).'</pre>';die();
        return $this->template->fetch('gallery/create.tpl');


    }
    public function updateAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['update'])){
            $FolderId= isset($_POST['FolderId'])?$_POST['FolderId']:'';
            $title= isset($_POST['Title'])?$_POST['Title']:'';
            $type= isset($_POST['Type'])?$_POST['Type']:'';
            $photo= isset($_FILES['Photo'])?$_FILES['Photo']:'';
            $video= isset($_FILES['Video'])?$_FILES['Video']:'';
            // var_dump($_FILES['Video']['size']);die();
            if($_FILES['Video']['size']> 9*1024*1024){
                $mess= "file exceeds filesystem size limit";

                $galleryFolder=$this->model->listGalleryFolder();
                $this->template->assign('mess',$mess);
                $this->template->assign('folder',$galleryFolder);
                // echo '<pre>'.print_r($galleryFolder,true).'</pre>';die();
                return $this->template->fetch('gallery/create.tpl');
            }
            $data= array(
                'FolderId'=>$FolderId,
                'Photo'=>$photo,
                'Video'=>$video,
                'Title'=>$title,
                'Type'=>(int)$type,
            );
            // echo '<pre>'.print_r($data,true).'</pre>';die();
            $this->model->galleryUpdate($data,$id);
            return $this->indexAction();
        }

        $galleryFolder=$this->model->listGalleryFolder();
        $this->template->assign('folder',$galleryFolder);
        $gallery= $this->model->galleryId($id);
       // echo '<pre>'.print_r($gallery,true).'</pre>';die();
        $this->template->assign('gallery',$gallery);
        return $this->template->fetch('gallery/update.tpl');
        // echo 'update test';
    }
    public function deleteAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['delete'])){
            //  echo $id;die();
            $this->model->galleryDelete($id);
            return $this->indexAction();
        }
        $galleryFolder=$this->model->listGalleryFolder();
        $this->template->assign('folder',$galleryFolder);
        $gallery= $this->model->galleryId($id);
        // echo '<pre>'.print_r($gallery,true).'</pre>';die();
        $this->template->assign('gallery',$gallery);
        return $this->template->fetch('gallery/delete.tpl');
    }
}
