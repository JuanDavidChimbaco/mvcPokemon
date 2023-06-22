<?php
include_once "../model/usuario.php";

$usu = new Model\Usuario();

// Obtener el ID del formulario o de la solicitud
$id = $_GET['id'];

$usu->setId($id);
$result = $usu->delete();

echo json_encode($result);

unset($usu);
?>