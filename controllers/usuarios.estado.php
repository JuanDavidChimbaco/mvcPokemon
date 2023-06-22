<?php 

include_once "../model/usuario.php";

$id = $_POST['id'];
$estado = $_POST['estado'];

if($estado == "A"){
    $estado = 'I';
}else{
    $estado = 'A';
}

$usuM = new model\Usuario();

$usuM->setId($id);
$usuM->setEstado($estado);
$result = $usuM->estado();

echo json_encode($result);

unset($usuM);
unset($result);
?>