<?php
/**
 * Created by PhpStorm.
 * User: Tam Tran
 * Date: 5/30/2015
 * Time: 11:30 AM
 */

namespace Models;


class ReportModel extends BaseModel {
    public function __construct()
    {
        parent::__construct();
    }

    public function listReport($start,$limit,$count=0,$search='')
    {
        $arrSearch=array();
        $strLike = '';
        if(!empty($search)){
            $strLike = ' WHERE (`unique_id_ordernumber` like ?)';
        }
//echo $search;die();
        $query="SELECT count(*) AS total FROM `transactions` "." ".$strLike.' ORDER BY `date` desc';

        $querylimit = "SELECT * FROM `transactions` "." ".$strLike.' ORDER BY `date` desc  LIMIT ?, ?';
        //echo $query;die();
        if(!empty($search)) {     
            $arrSearch[] = array('%'.$search.'%',\PDO::PARAM_STR);
        }


        if($count==1) {
            $this->database->setQuery($query);
            $total = $this->database->loadRow($arrSearch);
            return $total->total;
        }

        $arrSearch[] =array($start,\PDO::PARAM_INT);
        $arrSearch[] =array($limit,\PDO::PARAM_INT);

        $this->database->setQuery($querylimit);
        $result = $this->database->loadAllRows($arrSearch);
        
//echo '<pre>'.print_r($querylimit,true).'</pre>';die();
        return $result;
    }

    public function insertReport($transations)
    {
        foreach($transations as $item) {
            $query = 'INSERT INTO `transactions`(merchantId,date,unique_id_ordernumber,programma_name,programa_prepayment_status,
                      time_of_visit,time_in_session,time_last_modified,evento_name,reason,site_name,elem_grafico_name,status,
                      amount,commission,custom_id) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
            $arrData = array(
                array($item['merchantId'],\PDO::PARAM_INT),
                array($item['date'],\PDO::PARAM_STR),
                array($item['unique_id_ordernumber'],\PDO::PARAM_STR),
                array($item['programma_name'],\PDO::PARAM_STR),
                array($item['programa_prepayment_status'],\PDO::PARAM_STR),
                array($item['time_of_visit'],\PDO::PARAM_STR),
                array($item['time_in_session'],\PDO::PARAM_STR),
                array($item['time_last_modified'],\PDO::PARAM_STR),
                array($item['evento_name'],\PDO::PARAM_STR),
                array($item['reason'],\PDO::PARAM_STR),
                array($item['site_name'],\PDO::PARAM_STR),
                array($item['elem_grafico_name'],\PDO::PARAM_STR),
                array($item['status'],\PDO::PARAM_STR),
                array($item['amount'],\PDO::PARAM_STR),
                array($item['commission'],\PDO::PARAM_STR),
                array($item['custom_id'],\PDO::PARAM_STR)
            );
            $this->database->setQuery($query);
            $this->database->execute($arrData);
        }

        return true;
    }

} 