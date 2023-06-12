<?php 

include_once "../model/producto.php";

$id = $_POST['id'];
$estado = $_POST['estado'];

if($estado == "A"){
    $estado = 'I';
}else{
    $estado = 'A';
}

$proM = new model\Producto();

$proM->setId($id);
$proM->setEstado($estado);
$result = $proM->estado();

echo json_encode($result);

unset($proM);
unset($result);
?>