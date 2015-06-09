<?php
/**
 * Created by PhpStorm.
 * User: Tam Tran
 * Date: 6/7/2015
 * Time: 11:41 AM
 */

namespace Models;


class AccountModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listAccount($start, $limit,$count=0)
    {
        $sql = 'SELECT COUNT(*) AS total FROM accounts';
        if($count==1) {
            $this->database->setQuery($sql);
            return $this->database->loadRow();
        }
        $sql = 'SELECT * FROM accounts ORDER BY `name` asc LIMIT ?,?';
        $data = array(
            array((int)$start,\PDO::PARAM_INT),
            array((int)$limit,\PDO::PARAM_INT)
        );
        $this->database->setQuery($sql);
        $result = $this->database->loadAllRows($data);
        return $result;
    }

    public function getAllAccount($account_id=null)
    {
        $strAccount = '';
        $data = array();
        if($account_id!=null) {
            $strAccount = 'WHERE id=? ';
            $data[] =array((int)$account_id,\PDO::PARAM_INT);
        }
        $sql = 'SELECT * FROM accounts '.$strAccount.' ORDER BY `name` asc';

        $this->database->setQuery($sql);
        $result = $this->database->loadAllRows($data);
        return $result;
    }

    public function viewAccount($id)
    {
        $sql = 'SELECT * FROM accounts WHERE id=?';
        $data = array(
            array($id,\PDO::PARAM_INT)
        );
        $this->database->setQuery($sql);
        $result = $this->database->loadRow($data);
        return $result;
    }

    public function activeAccount($id,$status)
    {
        $sql = 'UPDATE accounts  SET `status`='.$status.'  WHERE `id`='.$id;
         $this->database->beginTransaction();
        $this->database->setQuery($sql);
        $result = $this->database->exec($sql);
        if($result === false) {
            $this->database->rollBack();
            return false;
        }

        if($status==0) {
            $sql = 'UPDATE users  SET `status`='.$status.' WHERE `account_id`='.$id;
            $this->database->setQuery($sql);
            $result = $this->database->exec($sql);
            if($result === false) {
                $this->database->rollBack();
                return false;
            }
        }
        $this->database->commit();
        return true;
    }

    public function updateAccount($id,$params)
    {
        $data = array();
        $conditions='';

        foreach($params as $key=>$value) {
            $conditions.=$key.'=?,';
            if(is_numeric($value)){
                $data[] = array($value,\PDO::PARAM_INT);
            } else{
                $data[] = array($value,\PDO::PARAM_STR);
            }


        }

        if(!empty($conditions)) {
            $conditions=substr($conditions,0,strlen($conditions)-1);
        }

        $sql = 'UPDATE `accounts` SET '.$conditions.' WHERE `id`= ? ';
        $data[] = array($id,\PDO::PARAM_INT);
        $this->database->setQuery($sql);
        return $this->database->execute($data,1);
    }

    public function deleteAccount($id)
    {
        $sql = 'DELETE FROM accounts  WHERE `id`='.$id;
        $this->database->beginTransaction();
        $this->database->setQuery($sql);
        $result = $this->database->exec($sql);
        if($result === false) {
            $this->database->rollBack();
            return false;
        }


        $sql = 'DELETE FROM users WHERE `account_id`='.$id;
        $this->database->setQuery($sql);
        $result = $this->database->exec($sql);
        if($result === false) {
            $this->database->rollBack();
            return false;
        }

        $this->database->commit();
        return true;
    }

    public function createAccount($params)
    {
        $sql = 'INSERT INTO `accounts`(name,type,status,created_day,modified_day) VALUES(?,?,?,?,?)';
        $data = array(
            array($params['name'],\PDO::PARAM_STR),
            array($params['type'],\PDO::PARAM_INT),
            array($params['status'],\PDO::PARAM_INT),
            array($params['created_day'],\PDO::PARAM_INT),
            array($params['modified_day'],\PDO::PARAM_INT),
        );
        $this->database->setQuery($sql);
        return $this->database->execute($data,1);
    }

} 