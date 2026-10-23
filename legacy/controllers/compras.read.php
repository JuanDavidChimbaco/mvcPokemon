<?php
include_once "../model/compra.php";

$compra = new Model\Compra();
session_start();
$idrol = $_SESSION['rol'];
$id = $_SESSION['id'];

if ($idrol = 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $result = $compra->read();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $estado = $_POST['estado'];

        if ($estado == "A") {
            $estado = 'I';
        } else {
            $estado = 'A';
        }

        $compra->setId($id);
        $compra->setEstado($estado);
        $result = $compra->estado();
    }
}
echo json_encode($result);
unset($proM);
unset($result);