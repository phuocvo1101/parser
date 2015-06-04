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

    public function insert($invoices)
    {
        foreach($invoices as $invoice) {
            $query = 'INSERT INTO ';
           // $this->database->setQuery()
        }
    }
}