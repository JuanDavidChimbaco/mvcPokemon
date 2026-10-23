<?php
include_once "../model/producto.php";

$productoM = new Model\Producto();

session_start();
$id = $_SESSION['id'];

$productoM->setUsuarioCreacion($id);
$productoM->setUsuarioModificacion($id);

$productoM->setNombrePro($_POST['nombrePro']);
$productoM->setPrecioPro($_POST['precioPro']);
$productoM->setCantidadPro($_POST['cantidadPro']);
$productoM->setCategoria($_POST['categoria']);
$productoM->setUrlFoto($_POST['fotoUrl']);

$result = $productoM->create();

if ($result === "Producto Creado") {
    // La consulta se realizó correctamente
    $response = [
      "success" => true,
      "message" => "Producto creado correctamente"
    ];
  } else {
    // Hubo un error en la consulta
    $response = [
      "success" => false,
      "message" => "Error al crear el producto"
    ];
  }

header('Content-Type: application/json');
echo json_encode($response);

unset($result);
unset($productoM);
?>