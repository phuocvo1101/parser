<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\EventModel;

class EventController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new EventModel();
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

        $Pagination = new Pagination($limit,'index.php?controller=event&action=index&search='.$search);//,$base_url

        //$totalPages = $Pagination->totalPages($totalRecord);
        $limit = (int)$Pagination->limit;
        $start = (int)$Pagination->start();

        $result = $this->model->listEvent($start,$limit,$search);
        $totalRecord=$result['count'];
        // var_dump($totalRecord);die();
        $totalPages = $Pagination->totalPages($totalRecord);

        $listPage= $Pagination->listPages($totalPages);

        $this->template->assign('events',$result['data']);
        $this->template->assign('search',$search);
        $this->template->assign('limit',$limit);
        $this->template->assign('totalrecords',$totalRecord);
        $this->template->assign('totalpages',$totalPages);
        $this->template->assign('listPage',$listPage);

        return $this->template->fetch('event/index.tpl');


    }
    public function createAction()
    {
        if(isset($_POST['create'])){
            $title= isset($_POST['Title'])?$_POST['Title']:'';
            $photo= isset($_FILES['Photo'])?$_FILES['Photo']:'';
            $content= isset($_POST['Content'])?$_POST['Content']:'';
            $eventDate= isset($_POST['EventDate'])?$_POST['EventDate']:'';
            $eventYear= isset($_POST['EventYear'])?$_POST['EventYear']:'';
            $heEvent= isset($_POST['HEvents'])?$_POST['HEvents']:1;
            $important= isset($_POST['Important'])?$_POST['Important']:1;
            $memos= isset($_POST['Memos'])?$_POST['Memos']:1;

            // var_dump($photo);die();
            $data= array(
                'Photo'=>$photo,
                'Title'=>$title,
                'Content'=>$content,
                'EventDate'=>$eventDate,
                'EventYear'=>(int)$eventYear,
                'HEvents'=>(boolean)$heEvent,
                'Important'=>(boolean)$important,
                'Memos'=>(boolean)$memos,

            );
          //  var_dump($data);die();
            $this->model->eventCreate($data);
            return $this->indexAction();
        }
        return $this->template->fetch('event/create.tpl');


    }
    public function updateAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['update'])){
            $title= isset($_POST['Title'])?$_POST['Title']:'';
            $photo= isset($_FILES['Photo'])?$_FILES['Photo']:'';
            $content= isset($_POST['Content'])?$_POST['Content']:'';
            $eventDate= isset($_POST['EventDate'])?$_POST['EventDate']:'';
            $eventYear= isset($_POST['EventYear'])?$_POST['EventYear']:'';
            $heEvent= isset($_POST['HEvents'])?$_POST['HEvents']:1;
            $important= isset($_POST['Important'])?$_POST['Important']:1;
            $memos= isset($_POST['Memos'])?$_POST['Memos']:1;

            // var_dump($photo);die();
            $data= array(
                'Photo'=>$photo,
                'Title'=>$title,
                'Content'=>$content,
                'EventDate'=>$eventDate,
                'EventYear'=>(int)$eventYear,
                'HEvents'=>(boolean)$heEvent,
                'Important'=>(boolean)$important,
                'Memos'=>(boolean)$memos,

            );
            $this->model->eventUpdate($data,$id);
            return $this->indexAction();
        }

        $event= $this->model->eventId($id);
      // echo '<pre>'.print_r($event).'</pre>';die();
        $this->template->assign('event',$event);
        return $this->template->fetch('event/update.tpl');
        // echo 'update test';
    }
    public function deleteAction()
    {
        $id= $_GET['id'];
        if(isset($_POST['delete'])){
            //  echo $id;die();
            $this->model->eventDelete($id);
            return $this->indexAction();
        }
        $event= $this->model->eventId($id);
        // echo '<pre>'.print_r($event).'</pre>';die();
        $this->template->assign('event',$event);
        return $this->template->fetch('event/delete.tpl');
    }
}
