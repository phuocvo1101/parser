<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\ImageSliderModel;

class ImageSliderController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ImageSliderModel();
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

        $Pagination = new Pagination($limit,'index.php?controller=imageSlider&action=index&search='.$search);//,$base_url

        //$totalPages = $Pagination->totalPages($totalRecord);
        $limit = (int)$Pagination->limit;
        $start = (int)$Pagination->start();

        $result = $this->model->listImageSlider($start,$limit,$search);
        $totalRecord=$result['count'];
        // var_dump($totalRecord);die();
        $totalPages = $Pagination->totalPages($totalRecord);

        $listPage= $Pagination->listPages($totalPages);

        $this->template->assign('images',$result['data']);
        $this->template->assign('search',$search);
        $this->template->assign('limit',$limit);
        $this->template->assign('totalrecords',$totalRecord);
        $this->template->assign('totalpages',$totalPages);
        $this->template->assign('listPage',$listPage);

        return $this->template->fetch('imageSlider/index.tpl');


    }
    public function createAction()
    {
        if(isset($_POST['create'])){
            $title= isset($_POST['EventId'])?$_POST['EventId']:'';
            $photo= isset($_FILES['Photo'])?$_FILES['Photo']:'';
            // var_dump($photo);die();
            $data= array(
                'Photo'=>$photo,
                'EventId'=>$title,
            );
            //var_dump($data);die();
            $this->model->imageSliderCreate($data);
            return $this->indexAction();
        }
        $event=$this->model->listEvent();
      //  var_dump($event);die();
        $this->template->assign('event',$event);
        return $this->template->fetch('imageSlider/create.tpl');


    }
    public function updateAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['update'])){
            $title= isset($_POST['EventId'])?$_POST['EventId']:'';
            $photo= isset($_FILES['Photo'])?$_FILES['Photo']:'';
            // var_dump($photo);die();
            $data= array(
                'Photo'=>$photo,
                'EventId'=>$title,
            );
            // var_dump($data);die();

            $this->model->imageSliderUpdate($data,$id);
            return $this->indexAction();
        }

        $event=$this->model->listEvent();
        $image= $this->model->imageSliderId($id);

        $this->template->assign('event',$event);
        $this->template->assign('image',$image);
        return $this->template->fetch('imageSlider/update.tpl');
        // echo 'update test';
    }
    public function deleteAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['delete'])){
            //  echo $id;die();
            $this->model->imageSliderDelete($id);
            return $this->indexAction();
        }
        $event=$this->model->listEvent();
        $image= $this->model->imageSliderId($id);

        $this->template->assign('event',$event);
        $this->template->assign('image',$image);
        return $this->template->fetch('imageSlider/delete.tpl');
    }
}
