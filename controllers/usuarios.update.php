<?php
include_once "../model/usuario.php";

$usuM = new Model\Usuario();


$usuM->setId($_POST['id']);
$usuM->setTipoDocumento($_POST['tipoDoc']);
$usuM->setIdentificacion($_POST['identificacion']);
$usuM->setNombre($_POST['nombre']);
$usuM->setApellido($_POST['apellido']);
$usuM->setCorreo($_POST['correo']);
$usuM->setDireccion($_POST['direccion']);
$usuM->setTelefono($_POST['telefono']);
$usuM->setGenero($_POST['genero']);
$usuM->setIdRol($_POST['idRol']);

$updateResult = $usuM->update();

echo json_encode($updateResult);

unset($usuM);
unset($updateResult);
?>