<?php
include_once "../model/producto.php";

$producto = new Model\Producto();


$id = $_POST['id'];
$nombre = $_POST['nombrePro'];
$precio = $_POST['precioPro'];
$cantidad = $_POST['cantidadPro'];
$descripcion = $_POST['descripPro'];
$urlFoto = $_POST['fotoUrl'];

$producto->setid($id);
$producto->setNombrePro($nombre);
$producto->setPrecioPro($precio);
$producto->setCantidadPro($cantidad);
$producto->setCategoria($descripcion);
if ($urlFoto!==""){
    $producto->setUrlFoto($urlFoto);
}

$updateResult = $producto->update();

echo json_encode($updateResult);

unset($rol);
unset($updateResult);
?>