<?php
include_once "../model/rol.php";

$rol = new Model\Rol();

$rol->setNameRol($_POST['nombreRol']);
$rol->setEstadoRol($_POST['estado']);
$updateResult = $rol->update();

echo json_encode($updateResult);

unset($rol);

?>