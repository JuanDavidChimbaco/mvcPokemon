<?php
session_start();
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header('Location: ./views/login.php');
    exit;
}else{
    $idRol = $_SESSION["rol"];
    if ($idRol == 1) {
        header('Location: ./views/dashboard.php');
        exit;
    }else{
        header('Location: ./views/PokeApi/index.php');
        exit;
    }
}

?>