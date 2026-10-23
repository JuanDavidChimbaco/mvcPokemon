<?php 
include_once '../model/usuario.php';

$correo = $_GET['txtCorreo'];
$password = $_GET['txtPassword'];

$usuarioM = new \Model\Usuario;

$usuarioM->setCorreo($correo);
$usuarioM->setPassword($password);

$response = $usuarioM->login();

if (isset($response[0]['correo']) && !empty($response[0]['correo'])){
    session_start();
    $_SESSION['id'] = $response [0]['id'];
    $_SESSION['tipoDoc'] = $response [0]['tipoDoc'];
    $_SESSION['identificacion'] = $response [0]['identificacion'];
    $_SESSION['nombre'] = $response [0]['nombre'];
    $_SESSION['apellido'] = $response [0]['apellido'];
    $_SESSION['correo'] = $response [0]['correo'];
    $_SESSION['direccion'] = $response [0]['direccion'];
    $_SESSION['telefono'] = $response [0]['telefono'];
    $_SESSION['genero'] = $response [0]['genero'];
    $_SESSION['rol'] = $response [0]['idRol'];
}

echo json_encode($response);

unset($usuarioM);
unset($response);
?>