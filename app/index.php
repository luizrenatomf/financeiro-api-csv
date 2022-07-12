<?php 

require_once("inc/config.php");
require_once("inc/api_functions.php");

$variables = [
    "id" => 2,
    "descricao" => "Supermercado",
    "valor" => 209.71,
    "tipo" => "DF",
    "data" => "10/07/2022"
];

echo '<pre>';

// $results = api_request('status', 'GET', $variables);
// $results = api_request('all_bills','GET');
// $results = api_request('insert_bill','POST',$variables);
// $results = api_request('find_csv','POST',array('id' => 2));
// $results = api_request('find_bills','POST',array('descricao' => 'SUPERMERCADO', 'valor' => 130.99));
$results = api_request('data_filter_csv','POST',array('data' => '14/08/1993'));
print_r($results);
