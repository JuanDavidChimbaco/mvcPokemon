<?php
include_once "../model/producto.php";

$proC = new Model\Producto();

// Obtener el valor del parámetro 'id'
$categoria = $_GET['categoria'];

$proC->setCategoria($categoria);
$result = $proC->readCat();

echo json_encode($result);

unset($proC);
unset($result);

?>