<?php

$cep = '42850000';
$login = '';
$senha = '';
$weight = 0;

$cep_dest = $_POST['postcode'];

include 'get_shipping_cost.inc.php';

// header('Cache-Control: no-cache, must-revalidate');
// header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: text/json; charset=utf-8');

$data = [];

if (!isset($frete['error'])) {
    foreach ($frete as $key => $value) {
        $quote = [['code' => 'ukd_correios.'.$key, 'title' => strtoupper($key), 'text' => 'R$'.$frete[$key]['valor']]];

        array_push($data, ['title' => '', 'quote' => $quote]);
    }

    echo json_encode(['shipping_method' => $data]);
} else {
    echo json_encode(['error' => ['warning' => $frete['error']]]);
}
