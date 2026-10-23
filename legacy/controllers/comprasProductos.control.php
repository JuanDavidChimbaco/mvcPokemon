<?php
include_once "../model/compraProducto.php";
include_once "../model/compra.php";

date_default_timezone_set('America/Bogota');
session_start();

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];


    $compra = new Model\Compra();
    $fecha = date('Y-m-d H:i:s');
    $ultimoID = "";

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['code'])) {

        $codigoCom = $_GET['code'];

        $compra->setCodigoCom($codigoCom);
        $compra->setFechaCom($fecha);
        $compra->setUsuarioCreacion($id);
        $compra->setUsuarioModificacion($id);
        $lastID = $compra->create();
        echo json_encode($lastID);

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $datos = file_get_contents('php://input');
        $data = json_decode($datos, true);
        if (is_array($data)) {
            $compraProducto = new Model\CompraProducto();

            $precioTotal = 0;
            $cantidadTotal = 0;
            $LsID = $data['ultimoID'];

            foreach ($data['productosCarrito'] as $item) {
                $producto = $item['producto'];
                $idP = $producto['id'];
                $precio = $producto['precioPro'];
                $cantidad = $producto['cantidad'];
                $subtotal = $precio * $cantidad;
                $precioTotal += $subtotal;
                $cantidadTotal += $cantidad;
                $cantidadProductos = count($data['productosCarrito']);
                $compraProducto->setIdCom($LsID);
                $compraProducto->setIdPro($idP);
                $compraProducto->setCantidadProCom($cantidad);
                $compraProducto->setUsuarioCreacion($id);
                $compraProducto->setUsuarioModificacion($id);
                $result = $compraProducto->create();
            }

            echo json_encode($result);
        } else {
            echo json_encode(['error' => 'Datos inválidos']);
        }
    } else {
        echo json_encode(['error' => 'No se han recibido datos ']);
    }

}