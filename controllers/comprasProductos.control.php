<?php
include_once "../model/compraProducto.php";

$compraProducto = new Model\CompraProducto();
session_start();

$datos = file_get_contents('php://input');

$data = json_decode($datos);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($datos)) {

    $datoslistos=$data[0];
    
    $compraProducto->setIdPro($datoslistos->producto->id);
    

    $result = $compraProducto->getIdPro();
    echo json_encode($result);
    // echo json_encode(['status' => 'Ok']);

} else {
    echo json_encode(['error' => 'No se han recibido datos']);
}
?>


