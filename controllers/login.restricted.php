<?php 
session_start();

if ($_SESSION["rol"]==1){
    $response = true;
}else{
    $response = false;
}
echo json_encode($response)
?>