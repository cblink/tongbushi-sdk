<?php

require __DIR__.'/vendor/autoload.php';

$config = [
    'partner'=> 'asdfasdf',
    'consumer_key' => 'aaaa',
    'company_ouid' => 'bbbb',
    'secret' => 'ccccc',
    'debug' => false
];

$tongbushi = new \Cblink\Tongbushi\Tongbushi($config);

$tongbushi->tongbushi->getProduct('allshopsku.action', [1,2,3]);
var_dump(123123);exit;




try{
    $tongbushi->tongbushi->getProduct();
}catch (Exception $exception) {
    var_dump($exception);exit;
}