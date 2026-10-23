<?php
include_once "../model/usuario.php";

$usuarioM = new Model\Usuario();


$usuarioM->setTipoDocumento($_POST['tipoDoc']);
$usuarioM->setIdentificacion($_POST['identificacion']);
$usuarioM->setNombre($_POST['nombre']);
$usuarioM->setApellido($_POST['apellido']);
$usuarioM->setCorreo($_POST['correo']);
$usuarioM->setPassword($_POST['pass']);
$usuarioM->setDireccion($_POST['direccion']);
$usuarioM->setTelefono($_POST['telefono']);
$usuarioM->setGenero($_POST['genero']);
$usuarioM->setIdRol($_POST['idRol']);

$result = $usuarioM->create();

echo json_encode($result);

unset($result);
unset($usuarioM);
?>