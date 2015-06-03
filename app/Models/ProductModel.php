<?php
namespace Models;

use GuzzleHttp\Client;
class ProductModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }
    public function listProduct($page,$limit,$search='')
    {
        $arrSearch=array();
        $result = array();
        $client = new Client([]);
        $strsearch="";
        if($search != ""){
            $strsearch= ";q=".$search;
        }
        //echo $page."-".$limit;die();
        $pagelimit = ($limit*$page)+$limit;
        $urllimit = 'http://api.tradedoubler.com/1.0/products.json;page='.$page.';pageSize='.$limit.$strsearch.';limit='.$pagelimit.'?token=BFDFD4A24D6DBD5F9D0B091D9714B5460891B53B';
//echo $urllimit;die();
        $response = $client->get($urllimit);
        $stream = $response->getBody(true);
        $content = $stream->getContents();
        $result = json_decode($content,true);
//echo '<pre>'.print_r($result,true).'</pre>';die();
        if(!isset($result['products'])) {
            return array(
                'total' => 0,
                'products' => array()
            );
        }

        return array(
            'total' => $result['productHeader']['totalHits'],
            'products' => $result['products']
        );
    }
}