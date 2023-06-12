<?php
include_once "../model/usuario.php";

$usuM = new Model\Usuario();

$result = $usuM->read();

echo json_encode($result);
unset($usuM);
unset($result);