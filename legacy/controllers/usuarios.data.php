<?php
include_once "../model/usuario.php";

session_start();
$id = $_SESSION['id'];

$user = new Model\Usuario();

$user->setId($id);
$result = $user->readID();

echo json_encode($result);
unset($user);
unset($result);

?>