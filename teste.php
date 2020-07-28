<?php
require __DIR__ . '/vendor/autoload.php';
header('Content-type: application/json');
use Jarouche\ViaCEP\HelperViaCep;
$id =  trim($_GET["cep"]);
//Using Helper
$result = HelperViaCep::getBuscaViaCEP('Json', $id);

echo $result;