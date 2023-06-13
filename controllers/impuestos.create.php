<?php
include_once "../model/impuesto.php";

$imp = new Model\Impuesto();
$imp2 = new Model\Impuesto();

session_start();
$idUser = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Solicitud de creación
    $data = json_decode(file_get_contents('php://input'));

    $imp->setNameImp($data->nombre);
    $imp->setPercentage($data->porcentaje);
    $imp->setUserCreated($idUser);
    $imp->setUserModified($idUser);

    // $data2 = json_decode(file_get_contents('php://input'));
    $estado = $data->estadoE;
    if($estado == "A"){
        $estado = "I";
    }else{
        $estado = "A";
    }
    
    $imp2->setId($data->idE);
    $imp2->setStatus($estado);

    $result = $imp->create();
    $result2 = $imp->read();
    $result3 = $imp2->estado();

    $response = array(
        'result' => $result,
        'result2' => $result2,
        'result3' => $result3
    );
    header('Content-Type: application/json');
    echo json_encode($response);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Solicitud de lectura
    $result2 = $imp->read();

    $response = array(
        'result2' => $result2
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}



unset($imp);
unset($result);
unset($result2);
unset($result3);
unset($response);
?>
