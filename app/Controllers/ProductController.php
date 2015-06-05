<?php
namespace Controllers;

use Controllers\BaseController;
use Controllers\IBaseController;
use Libraries\Pagination;
use Models\ProductModel;

class ProductController extends BaseController implements IBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ProductModel();
    }

    public function indexAction()
    {

        if (isset($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
        } else {
            $search = '';
        }

        if (isset($_POST['go'])) {
            $search = $_POST['search'] ? $_POST['search'] : '';
        }

        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;


        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
        $page = ($start/$limit) + 1;

        $result = $this->model->listProduct($page, $limit, $search);
        $Pagination = new Pagination($limit, 'index.php?controller=product&action=index&search=' . $search);//,$base_url

        $totalRecord = $result['total'];
        $totalPages = $Pagination->totalPages($totalRecord);
        $listPage = $Pagination->listPages($totalPages);

        $this->template->assign('products', $result['products']);
        $this->template->assign('search', $search);
        $this->template->assign('limit', $limit);
        $this->template->assign('start', $start);
        $this->template->assign('totalrecords', $totalRecord);
        $this->template->assign('totalpages', $totalPages);
        $this->template->assign('listPage', $listPage);
        return $this->template->fetch('product/index.tpl');
    }
}
