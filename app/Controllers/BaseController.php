<?php

namespace Controllers;


class BaseController {
    protected  $template;
    public function __construct()
    {
        global $smarty;
        $this->template= $smarty;
        if(!$this->checkLogin()) {
            if(isset($_REQUEST['controller']) && $_REQUEST['controller']=='systemuser' && isset($_REQUEST['controller']) && $_REQUEST['action']=='login') {
                return;
            } else{
                $this->redirect('systemuser','login');
            }


        } else{
            if(isset($_REQUEST['controller']) && $_REQUEST['controller']=='systemuser'  && $_REQUEST['action']=='login') {
                $this->redirect();
            }
        }
    }

    public function redirect($controller='',$action='',$params=array())
    {
        $url = 'index.php?controller='.$controller.'&action='.$action;
        if($controller=='' || $action=='') {
            $url = 'index.php';
        }

        foreach($params as $key=>$item) {
            $url.='&'.$key.'='.$item;
        }

        header('location:'.$url);
        exit();
    }

    public function checkLogin()
    {
        if(!isset($_SESSION['user_id'])) {
            return false;
        }
        return true;
    }

} 