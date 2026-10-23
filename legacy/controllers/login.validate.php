<?php 

session_start();

if (isset($_SESSION["id"]) and !empty($_SESSION["id"])){
    $response = true;
}else{
    $response = false;
}
echo json_encode($response);

?>