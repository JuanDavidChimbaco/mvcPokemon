<?php
include_once "../model/producto.php";

$proM = new Model\Producto();

$result = $proM->read();

echo json_encode($result);
unset($proM);
unset($result);