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
    $_SESSION['nombre'] = $response [0]['nombre'];
    $_SESSION['apellido'] = $response [0]['apellido'];
    $_SESSION['correo'] = $response [0]['correo'];
    $_SESSION['rol'] = $response [0]['idRol'];
}

echo json_encode($response);

unset($usuarioM);
unset($response);
?>