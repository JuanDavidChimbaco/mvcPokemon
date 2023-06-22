<?php
include_once "../model/producto.php";

$proM = new Model\Producto();

// Obtener el valor del parámetro 'id'
$id = $_GET['id'];

$proM->setId($id);
$result = $proM->readID();

echo json_encode($result);
unset($proM);
unset($result);

?>