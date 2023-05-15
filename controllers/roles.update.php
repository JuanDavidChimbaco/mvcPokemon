<?php
include_once "../model/rol.php";

$rol = new Model\Rol();

// obtener los datos del formulario
$data = json_decode(file_get_contents("php://input"), true);

$rol->setid($data['id']);
$rol->setNameRol($data['rol']);
$rol->setEstadoRol($data['estado']);

$updateResult = $rol->update();

echo json_encode($updateResult);

unset($rol);

?>