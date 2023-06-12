<?php
include_once "../model/usuario.php";

$productoM = new Model\Usuario();


$productoM->setTipoDocumento($_POST['tipoDoc']);
$productoM->setIdentificacion($_POST['identificacion']);
$productoM->setNombre($_POST['nombre']);
$productoM->setApellido($_POST['apellido']);
$productoM->setCorreo($_POST['correo']);
$productoM->setPassword($_POST['pass']);
$productoM->setDireccion($_POST['direccion']);
$productoM->setTelefono($_POST['telefono']);
$productoM->setGenero($_POST['genero']);
$productoM->setIdRol($_POST['idRol']);

$result = $productoM->create();

echo json_encode($result);

unset($result);
unset($productoM);
?>