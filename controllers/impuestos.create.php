<?php
include_once "../model/impuesto.php";

$impCreate = new Model\Impuesto();
$impRead = new Model\Impuesto();
$impStatus = new Model\Impuesto();
$impDelete = new Model\Impuesto();
$impReadID = new Model\Impuesto();
$impUpdate = new Model\Impuesto();

session_start();
$idUser = $_SESSION['id'];

$data = json_decode(file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Solicitud de creación

    $impCreate->setNameImp($data->nombre);
    $impCreate->setPercentage($data->porcentaje);
    $impCreate->setUserCreated($idUser);
    $impCreate->setUserModified($idUser);

    $estado = $data->estadoE;
    if($estado == "A"){
        $estado = "I";
    }else{
        $estado = "A";
    }
    
    $impStatus->setId($data->idE);
    $impStatus->setStatus($estado);

    $impDelete->setId($data->idD);

    $impUpdate->setId($data->id);
    $impUpdate->setNameImp($data->nombreImp);
    $impUpdate->setPercentage($data->porcentajeImp);
    $impUpdate->setUserModified($idUser);
    
    $result = $impCreate->create();
    $result3 = $impStatus->estado();
    $result5 = $impDelete->delete();
    $result6 = $impUpdate->update();

    $response = array(
        'result' => $result,
        'result3' => $result3,
        'result5' => $result5,
        'result6' => $result6
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if ($id !== false) {
        $impReadID->setId($id);
    }
    $result4 = $impReadID->readID();
    
    $result2 = $impRead->read();
    

    $response = array(
        'result2' => $result2,
        'result4' => $result4
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}



unset($impCreate);
unset($impRead);
unset($impReadID);
unset($impStatus);
unset($impDelete);
unset($result);
unset($result2);
unset($result3);
unset($result4);
unset($result5);
unset($response);
?>
