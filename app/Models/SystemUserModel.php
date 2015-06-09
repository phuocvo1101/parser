<?php
/**
 * Created by PhpStorm.
 * User: Tam Tran
 * Date: 6/7/2015
 * Time: 11:25 PM
 */

namespace Models;


class SystemUserModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listUser($start, $limit,$count=0, $account_id=null)
    {
        $data = array();
        $strAccount = '';

        if($account_id!=null) {
            $strAccount = 'WHERE u.account_id=? ';
            $data[] =array((int)$account_id,\PDO::PARAM_INT);
        }
        $sql = 'SELECT COUNT(*) AS total FROM users AS u '.$strAccount;

        if($count==1) {
            $this->database->setQuery($sql);
            return $this->database->loadRow($data);
        }

        $sql = 'SELECT u.*,a.name as account_name FROM `users` u
                LEFT JOIN `accounts` a ON u.account_id=a.id '.$strAccount.'
                ORDER BY `username` asc LIMIT ?,?';

        $data[] = array((int)$start,\PDO::PARAM_INT);
        $data[] =  array((int)$limit,\PDO::PARAM_INT);
        $this->database->setQuery($sql);
        $result = $this->database->loadAllRows($data);
        return $result;
    }

    public function viewUser($id)
    {
        $sql = 'SELECT u.*,a.name as account_name FROM `users` u
                LEFT JOIN `accounts` a ON u.account_id=a.id WHERE  u.id=?';
        $data = array(
            array($id,\PDO::PARAM_INT)
        );
        $this->database->setQuery($sql);
        $result = $this->database->loadRow($data);
        return $result;
    }

    public function activeUser($id,$status)
    {
        $sql = 'UPDATE users  SET `status`='.$status.'  WHERE `id`='.$id;
        $this->database->beginTransaction();
        $this->database->setQuery($sql);
        $result = $this->database->exec($sql);
        if($result === false) {
            $this->database->rollBack();
            return false;
        }

        $this->database->commit();
        return true;
    }

    public function updateUser($id,$params)
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

        $sql = 'UPDATE `users` SET '.$conditions.' WHERE `id`= ? ';
        $data[] = array($id,\PDO::PARAM_INT);
        $this->database->setQuery($sql);
        return $this->database->execute($data,1);
    }

    public function deleteUser($id)
    {
        $sql = 'DELETE FROM users  WHERE `id`='.$id;
        $this->database->beginTransaction();
        $this->database->setQuery($sql);
        $result = $this->database->exec($sql);
        if($result === false) {
            $this->database->rollBack();
            return false;
        }

        $this->database->commit();
        return true;
    }

    public function createUser($params)
    {
        $sql = 'INSERT INTO `users`(username,password,email,fullname,account_id,status) VALUES(?,?,?,?,?,?)';
        $data = array(
            array($params['username'],\PDO::PARAM_STR),
            array($params['password'],\PDO::PARAM_STR),
            array($params['email'],\PDO::PARAM_STR),
            array($params['fullname'],\PDO::PARAM_STR),
            array($params['account_id'],\PDO::PARAM_INT),
            array($params['status'],\PDO::PARAM_INT)
        );
        $this->database->setQuery($sql);
        return $this->database->execute($data,1);
    }

    public function checkExistUserName($username)
    {
        $sql = 'SELECT COUNT(*) AS total FROM `users` u
                 WHERE  u.username=?';
        $data = array(
            array($username,\PDO::PARAM_STR)
        );
        $this->database->setQuery($sql);
        $result = $this->database->loadRow($data);
        if(!$result) {
            return false;
        }

        if($result->total > 0) {
            return true;
        }
        return false;
    }


    public function checkPassword($id,$password)
    {
        $sql = 'SELECT COUNT(*) AS total FROM `users` u
                 WHERE  u.id=? AND u.password=?';

        $data = array(
            array((int)$id,\PDO::PARAM_INT),
            array($password,\PDO::PARAM_STR),
        );

        $this->database->setQuery($sql);
        $result = $this->database->loadRow($data);
        if(!$result) {
            return false;
        }
        if($result->total > 0) {
            return true;
        }
        return false;
    }

    public function checkLogin($params)
    {
        if(!isset($params['username'])) {
            return false;
        }

        if(!isset($params['password'])) {
            return false;
        }

        $sql = 'SELECT u.*,a.type,a.id as account_id FROM `users` AS u
              INNER JOIN `accounts` AS a ON u.account_id=a.id
              WHERE u.status=1 AND a.status=1 AND `username`=? AND `password` = ?';

        $this->database->setQuery($sql);
        $data = array(
            array($params['username'],\PDO::PARAM_STR),
            array($params['password'],\PDO::PARAM_STR)
        );
        $result = $this->database->loadRow($data);
        return $result;
    }

    public function updatePassword($params)
    {
        if(!isset($params['user_id'])) {
            return false;
        }

        if(!isset($params['password'])) {
            return false;
        }

        $sql = 'UPDATE `users` SET `password`=? WHERE `id`=?';
        $this->database->setQuery($sql);
        $data = array(
            array($params['password'],\PDO::PARAM_STR),
            array($params['user_id'],\PDO::PARAM_INT),
        );
        $result = $this->database->execute($data);
        return $result;
    }
} 