<?php
namespace Configs;


use Controllers\DashBoardController;
use Controllers\DayTitleController;
use Controllers\GalleryController;
use Controllers\GalleryFolderController;
use Controllers\StaticDataController;
use Controllers\TestController;
use Controllers\PushController;
use Controllers\EventController;
use Controllers\ImageSliderController;
use Controllers\UserController;
use Controllers\SystemUserController;
use Controllers\AccountController;
use Controllers\InstallationController;
class Routing {
    protected  $baseController;
    protected  $content;
    public function __construct()
    {
        $this->baseController = null;
    }

    public function getRouting()
    {
        $layout='layout.tpl';
        if(isset($_GET["controller"]) && isset($_GET['action'])) {

            switch(strtolower($_GET["controller"])) {

                case "test":
                    $this->baseController = new TestController();
                    break;
                case "installation":
                    $this->baseController = new InstallationController();
                    break;
                case "user":
                    $this->baseController = new UserController();
                    break;
                case "gallery":
                    $this->baseController = new GalleryController();
                    break;
                case "galleryfolder":
                    $this->baseController = new GalleryFolderController();
                    break;

                case "push":
                    $this->baseController = new PushController();
                    break;
                case "event":
                    $this->baseController = new EventController();
                    break;
                case "imageslider":
                    $this->baseController = new ImageSliderController();
                    break;
                case "daytitle":
                    $this->baseController = new DayTitleController();
                    break;
                case "staticdata":
                    $this->baseController = new StaticDataController();
                    break;
                case "dashboard":
                    $this->baseController = new DashBoardController();
                    break;
                case "account":
                    $this->baseController = new AccountController();
                    break;
                case "systemuser":
                    $this->baseController = new SystemUserController();
                    break;
                default:
                    $this->baseController = new DashBoardController();
                    break;
            }
            switch(strtolower($_GET['action'])) {
                case 'index':
                    $this->content = $this->baseController->indexAction();
                    break;
                case 'create':
                    $this->content = $this->baseController->createAction();
                    break;
                case 'update':
                    $this->content = $this->baseController->updateAction();
                    break;
                case 'delete':
                    $this->content = $this->baseController->deleteAction();
                    break;
                case 'send':
                    $this->content = $this->baseController->sendMessage();
                    break;
                case 'resend':
                    $this->content = $this->baseController->resendMessage();
                    break;
                case 'view':
                    $this->content = $this->baseController->viewAction();
                    break;
                case 'active':
                    $this->content = $this->baseController->activeAction();
                    break;
                case 'edit':
                    $this->content = $this->baseController->editAction();
                    break;
                case 'changepassword':
                    $this->content = $this->baseController->changePasswordAction();
                    break;
                case 'changepassworduser':
                    $this->content = $this->baseController->changePasswordUserAction();
                    break;
                case 'login':
                    $layout='loginlayout.tpl';
                    $this->content = $this->baseController->login();
                    break;
                case 'logout':
                    $this->content = $this->baseController->logout();
                    break;
                default:
                    $this->content =$this->baseController->indexAction();
                    break;
            }
        } else {
            $_GET['controller'] = 'dashboard';
            $_GET['action'] = 'index';
            $basecontroller = new DashBoardController();
            $this->content = $basecontroller->indexAction();
        }

        return array($this->content,$layout);
    }
}