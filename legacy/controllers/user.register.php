<?php
include_once "../model/usuario.php";

$user = new Model\Usuario();

$datos = json_decode(file_get_contents('php://input'));


if (!empty($datos)){
    $user->setTipoDocumento($datos->tipoDocumento);
    $user->setIdentificacion($datos->identificacion);
    $user->setNombre($datos->nombre);
    $user->setApellido($datos->apellido);
    $user->setCorreo($datos->correo);
    $user->setPassword($datos->contrasena);
    $user->setDireccion($datos->direccion);
    $user->setTelefono($datos->telefono);
    $user->setGenero($datos->genero);
    $user->setIdRol($datos->idRol);

    $result = $user->create();

    echo json_encode(array('result' => $result));
} else {
     echo json_encode('Null');
}


unset($user);
?>

