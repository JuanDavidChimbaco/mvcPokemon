<?php
include_once "../model/producto.php";

$producto = new Model\Producto();

date_default_timezone_set('America/Bogota');
$fecha = date('Y-m-d H:i:s');

session_start();
$idS = $_SESSION['id'];

$id = $_POST['id'];
$nombre = $_POST['nombrePro'];
$precio = $_POST['precioPro'];
$cantidad = $_POST['cantidadPro'];
$categoria = $_POST['categoriaPro'];
$urlFoto = $_POST['fotoUrl'];

$rol->setFechaModificacion($fecha);
$producto->setUsuarioModificacion($idS);
$producto->setid($id);
$producto->setNombrePro($nombre);
$producto->setPrecioPro($precio);
$producto->setCantidadPro($cantidad);
$producto->setCategoria($categoria);
if ($urlFoto!==""){
    $producto->setUrlFoto($urlFoto);
}

$updateResult = $producto->update();

echo json_encode($updateResult);

unset($rol);
unset($updateResult);
?>