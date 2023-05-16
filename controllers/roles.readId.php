

<?php
include_once "../model/rol.php";

$rol = new Model\Rol();

// Obtener el valor del parámetro 'id'
$id = $_GET['id'];

$rol->setId($id);
$result = $rol->readID();

echo json_encode($result);
unset($rol);
unset($result);

?>