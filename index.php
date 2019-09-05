<?php
require __DIR__ . '/vendor/autoload.php';
use \Cblink\Tongbushi\Tongbushi;

$config = [
    'debug' => true,
    'consumer_key' => '2FMf6HR9RDOCN0tL7QHJag',
    'company_ouid' => '15sYMEuPQ4mmNHc0JCZzVw',
    'partner' => 'WsJQcx3iFaoBR5iwEw4ECx',
    'secret' => 'd880d9400a9534408d52d7081abed8f5',

];


$tongbushi = new Tongbushi($config);


$data = $tongbushi->tongbushi->getProduct('allshopinfo.action', [
        'data'=>['dataVersion'=> 0],
        'consumerKey' => '2FMf6HR9RDOCN0tL7QHJag',
        'companyOuid' => '15sYMEuPQ4mmNHc0JCZzVw',
]);
var_dump($data);exit;