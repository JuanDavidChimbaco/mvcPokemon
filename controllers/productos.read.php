<?php
include_once "../model/producto.php";

$rol = new Model\Producto();

$result = $rol->read();

echo json_encode($result);
unset($rolM);
unset($result);