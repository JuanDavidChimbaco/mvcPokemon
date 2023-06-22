

<?php
include_once "../model/usuario.php";

$usuM = new Model\Usuario();

// Obtener el valor del parámetro 'id'
$id = $_GET['id'];

$usuM->setId($id);
$result = $usuM->readID();

echo json_encode($result);
unset($usuM);
unset($result);

?>