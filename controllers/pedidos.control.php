<?php
include_once "../model/pedido.php";

$pedido = new Model\Pedido();

session_start();
$idUser = $_SESSION['id'];

$data = json_decode(file_get_contents('php://input'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $action2 = $data->action;
    // Solicitud de creación
    switch ($action OR $action2) {
        case 'update':
            $pedido->setCodigoPed($_POST['codigoPedido']);
            $pedido->setFechaPed($_POST['fechaPedido']);
            $pedido->setIdUsu($_POST['cliente']);
            $pedido->setNombre($_POST['nombre']);
            $pedido->setDireccion($_POST['direccion']);
            $pedido->setTelefono($_POST['telefono']);
            $pedido->setTotalPed($_POST['totalPedido']);
            $pedido->setFormaPago($_POST['formaPago']);
            $pedido->setFechaEnvPedido($_POST['fechaEnvio']);           
            $pedido->setEstadoPedido($_POST['estadoPedido']);    
            $result = $pedido->update();
            $response = $result;
            break;
        case 'create':
            $pedido->setCodigoPed($_POST['codigoPedido']);
            $pedido->setFechaPed($_POST['fechaPedido']);
            $pedido->setIdUsu($_POST['cliente']);
            $pedido->setNombre($_POST['nombre']);
            $pedido->setDireccion($_POST['direccion']);
            $pedido->setTelefono($_POST['telefono']);
            $pedido->setTotalPed($_POST['totalPedido']);
            $pedido->setFormaPago($_POST['formaPago']);
            $pedido->setFechaEnvPedido($_POST['fechaEnvio']);           
            $pedido->setEstadoPedido($_POST['estadoPedido']);      
            $pedido->setUsuarioCreacion($idUser);      
            $pedido->setUsuarioModificacion($idUser);      

            $result = $pedido->create();
            $response = $result;
            break;

        case 'estado':
            $estado = $_POST['estado'];
        if($estado == "A"){
            $estado = "I";
        }else{
            $estado = "A";
        }
        
            $pedido->setId($_POST['id']);
            $pedido->setEstado($estado);
            $result = $pedido->estado();
            $response = $result;
            break;

        case 'delete':
            $pedido->setId($data->id);
            $result = $pedido->delete();
            $response = $result;
            break;
        default:
            $response = array(
                'error' => 'Accion no valida'
            );
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($response);

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $result = $pedido->read();

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    if ($id !== false) {
        $pedido->setId($id);
    }
    $result2 = $pedido->readId();
    $response[] = array(
        'result' => $result,
        'result2' => $result2
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}


unset($pedido);
unset($result);
unset($response);
?>
