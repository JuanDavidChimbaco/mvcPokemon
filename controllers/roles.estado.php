<?php 

include_once "../model/rol.php";

$id = $_POST['id'];
$estado = $_POST['estado'];

if($estado == "A"){
    $estado = 'I';
}else{
    $estado = 'A';
}

$rolM = new model\Rol();

$rolM->setId($id);
$rolM->setEstadoRol($estado);
$result = $rolM->estado();

echo json_encode($result);

unset($rolM);
unset($result);
?>