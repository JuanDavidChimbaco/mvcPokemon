<?php
include_once "../model/rol.php";

$rol = new Model\Rol();
$result = $rol->read();
echo json_encode($result);


$result = $rol->update();
unset($rolM);
unset($result);