<?php
include_once "../model/usuario.php";

$rol = new Model\Usuario();

$result = $rol->read();

echo json_encode($result);
unset($rolM);
unset($result);