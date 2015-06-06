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
        if(isset($_POST['submit'])){
            return $this->template->fetch('push/send.tpl');
        }
        if(isset($_POST['sendmess'])){
            $channel= isset($_POST['chan'])?$_POST['chan']:'';
            $mess= isset($_POST['message'])?$_POST['message']:'';
            if($channel==''){
                $target= 'everyone';
            }else{
                $target=$channel;
            }
          // echo $channel.'-'.$mess; die();
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
            //var_dump($data);die();
            $insertdata=$this->model->insertMessage($data);
           
           // echo '<pre>'.print_r($datamessage,true).'</pre>';die();
           
          //  echo '<pre>'.print_r($result,true).'</pre>';die();
        }
         $datamessage= $this->model->getdataMessage();
         $this->template->assign('message',$datamessage);
       //return $this->template->fetch('push/send.tpl');
        return $this->template->fetch('push/index.tpl');

    }
}
