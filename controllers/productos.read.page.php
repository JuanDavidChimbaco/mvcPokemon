<?php
include_once "../model/producto.php";

$proM = new Model\Producto();

$proM->setLimit($_GET['limit']);
$proM->setOffset($_GET['offset']);

$result = $proM->readPage();

echo json_encode($result);

unset($proM);
unset($result);