<?php 

include_once "../model/usuario.php";

$id = $_POST['id'];
$estado = $_POST['estado'];

if($estado == "A"){
    $estado = 'I';
}else{
    $estado = 'A';
}

$rolM = new model\Usuario();

$rolM->setId($id);
$rolM->setEstado($estado);
$result = $rolM->estado();

echo json_encode($result);

unset($rolM);
unset($result);
?>