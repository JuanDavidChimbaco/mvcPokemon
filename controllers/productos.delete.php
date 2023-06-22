<?php
include_once "../model/producto.php";

$pro = new Model\Producto();

// Obtener el ID del formulario o de la solicitud
$id = $_GET['id'];

$pro->setId($id);
$result = $pro->delete();

echo json_encode($result);

unset($pro);
?>