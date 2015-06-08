<?php
namespace Configs;


use Controllers\DashBoardController;
use Controllers\GalleryController;
use Controllers\GalleryFolderController;
use Controllers\TestController;
use Controllers\PushController;
use Controllers\EventController;
use Controllers\ImageSliderController;
class Routing {
    protected  $baseController;
    protected  $content;
    public function __construct()
    {
        $this->baseController = null;
    }

    public function getRouting()
    {
        if(isset($_GET["controller"]) && isset($_GET['action'])) {

            switch($_GET["controller"]) {

                case "test":
                    $this->baseController = new TestController();
                    break;
                case "gallery":
                    $this->baseController = new GalleryController();
                    break;
                case "galleryFolder":
                    $this->baseController = new GalleryFolderController();
                    break;
                case "push":
                    $this->baseController = new PushController();
                    break;
                case "event":
                    $this->baseController = new EventController();
                    break;
                case "imageSlider":
                    $this->baseController = new ImageSliderController();
                    break;
                case "dayTitle":
                    $this->baseController = new GalleryFolderController();
                    break;
                case "staticData":
                    $this->baseController = new PushController();
                    break;
                case "dashboard":
                    $this->baseController = new DashBoardController();
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

        return $this->content;
    }
}