<?php
include_once "../model/producto.php";

$productoM = new Model\Producto();

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData);

$productoM->setNombrePro($data->nameProducto);
$productoM->setPrecioPro($data->precioProducto);
$productoM->setCantidadPro($data->cantidadProducto);
$productoM->setDescripPro($data->descripcionProducto);

$result = $productoM->create();

header('Content-Type: application/json');
echo json_encode($result);

unset($rol);
?>