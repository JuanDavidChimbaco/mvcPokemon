<?php
include_once "../model/rol.php";

$rol = new Model\Rol();

// obtener los datos del formulario por json
// $data = json_decode(file_get_contents("php://input"), true);

// $rol->setid($data['id']);
// $rol->setNameRol($data['rol']);

$id = $_POST['id'];
$nombreRol = $_POST['nombreRol'];

$rol->setid($id);
$rol->setNameRol($nombreRol);

$updateResult = $rol->update();

echo json_encode($updateResult);

unset($rol);

?>