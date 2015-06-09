<?php
/**
 * Created by PhpStorm.
 * User: Tam Tran
 * Date: 5/30/2015
 * Time: 11:20 AM
 */

namespace Controllers;

//use Models\ReportModel;
//use Models\ProductModel;

class DashBoardController extends BaseController implements IBaseController {
    public function __construct()
    {
        parent::__construct();
       // $this->model = new ReportModel();
       // $this->m= new ProductModel();


    }

    public function indexAction()
    {
       // echo 'dar';die();
        /*$products= $this->m->listProduct(1,10);
        $totalproduct= $products['total'];
        $this->template->assign('totalproduct',$totalproduct);
        $totalRecord = $this->model->listReport(0,10,1);
        $this->template->assign('totalreport',$totalRecord);*/
        return $this->template->fetch('dashboard/index.tpl');
    }

} 