

<?php
include_once "../model/producto.php";

$rol = new Model\Producto();

// Obtener el valor del parámetro 'id'
$id = $_GET['id'];

$rol->setId($id);
$result = $rol->readID();

echo json_encode($result);
unset($rol);
unset($result);

?>