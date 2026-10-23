<?php
include_once "../model/rol.php";

$rol = new Model\Rol();
date_default_timezone_set('America/Bogota');
$fecha = date('Y-m-d H:i:s');

session_start();
$id = $_SESSION['id'];
$idRol = $_SESSION['rol'];
if ($idRol=1){
    $rol->setUsuarioModificacion($id);
    $rol->setid($_POST['id']);
    $rol->setNameRol($_POST['nombreRol']);
    $rol->setFechaModificacion($fecha);

    $updateResult = $rol->update();
    echo json_encode($updateResult);
}else{
    $updateResult = "No es Administrador";
    echo json_encode($updateResult);
}

unset($rol);
unset($updateResult);
?>