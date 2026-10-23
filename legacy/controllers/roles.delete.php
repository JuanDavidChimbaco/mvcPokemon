<?php
include_once "../model/rol.php";

$rol = new Model\Rol();

$id = $_GET['id'];

$rol->setId($id);
$result = $rol->delete();

echo json_encode($result);

unset($rol);
?>