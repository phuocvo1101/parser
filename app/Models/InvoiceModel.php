<?php
namespace Models;

use GuzzleHttp\Client;
class InvoiceModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listModel($username, $password)
    {
        $client = new Client([]);

        $url = 'https://tcguy.dbflex.net/secure/api/v2/41016/transaction/select.json';
        $response = $client->get($url,array('auth'=> array($username,$password)));
        $stream = $response->getBody(true);
        $content = $stream->getContents();
        $result = json_decode($content,true);
       return $result;
    }

   /* public function insert($invoices)
    {
        foreach($invoices as $item) {
            $query = 'INSERT INTO invoices(transactionRemoteID,amount,currencyCode,invoiceNumber,invoiceReference,
                      invoiceDescription,title,customerReference,firtName,lastName,companyName,jobDescription,street,
                      city,state,postCode,country,email,phone,mobile,fax,website,comment,cardHolder,cardNumber,
                      expiryDate,validFormDate,issueNumber,cvn,transactionType,date)
                       VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
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
                array($item['custom_id'],\PDO::PARAM_STR),
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
    }*/
}