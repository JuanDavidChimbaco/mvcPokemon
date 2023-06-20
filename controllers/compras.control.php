<?php
include_once "../model/compra.php";

$compra = new Model\Compra();

date_default_timezone_set('America/Bogota');
$fecha = date('Y-m-d H:i:s');

session_start();
$idUser = $_SESSION['id'];

$data = json_decode(file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $data->action;
    // Solicitud de creación
    switch ($action) {
        case 'update':
            $impUpdate->setId($data->idU);
            $impUpdate->setNameImp($data->nombreImp);
            $impUpdate->setPercentage($data->porcentajeImp);
            $impUpdate->setUserModified($idUser);
            $result6 = $impUpdate->update();
            $response = array(
                'result6' => $result6
            );
            break;
        case 'create':
            $cod = $data->codigoCom;
            $compra->setCodigoCom('C_0'.$cod);
            $compra->setFechaCom($fecha);
            $compra->setUsuarioCreacion($idUser);
            $compra->setUsuarioModificacion($idUser);
            $result = $compra->create();
            $response = array(
                'result' => $result
            );
            break;
        case 'estado':
            $estado = $data->estadoE;
        if($estado == "A"){
            $estado = "I";
        }else{
            $estado = "A";
        }
        
        $impStatus->setId($data->idE);
        $impStatus->setStatus($estado);
        $result3 = $impStatus->estado();
        $response = array(
            'result3' => $result3,
        );
        break;
        case 'delete':
            $impDelete->setId($data->idD);
            $result5 = $impDelete->delete();
            $response = array(
                'result5' => $result5
            );
            break;
        default:
            $response = array(
                'error' => 'Acción no válida'
            );
            break;
    }
    header('Content-Type: application/json');
    echo json_encode($response);

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $result2 = $impRead->read();

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if ($id !== false) {
        $impReadID->setId($id);
    }
    $result4 = $impReadID->readID();
    $response[] = array(
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
