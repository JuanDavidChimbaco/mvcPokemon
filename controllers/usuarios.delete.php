<?php
include_once "../model/usuario.php";

$rol = new Model\Usuario();

// Obtener el ID del formulario o de la solicitud
$id = $_GET['id'];

$rol->setId($id);
$result = $rol->delete();

echo json_encode($result);

unset($rol);
?>