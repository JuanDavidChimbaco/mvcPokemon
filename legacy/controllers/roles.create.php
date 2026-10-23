<?php
include_once "../model/rol.php";

$rol = new Model\Rol();

session_start();
$idrol = $_SESSION['rol'];
$id = $_SESSION['id'];

if ($idrol=1){
    $rol->setUsuarioCreacion($id);
    $rol->setNameRol($_POST["nameRol"]);
    $result = $rol->create();
    echo json_encode($result);
}else{
    $result = "No es Administrador";
    echo json_encode($result);
}

unset($rol);
unset($result);
?>