<?php

include_once "header.php";
//* body
?>
<form id="pedidosForm">
    <div class="row">
        <h2 class="text-center bg-dark text-white">Pedidos</h2>
    </div>

    <div class="row justify-content-center my-1">
        <form>
            <div class="col-5">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" name="pedido" id="pedido" placeholder="example"
                        required>
                    <label for="pedido">Codigo de Pedido:</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example">

                    </select>
                    <label for="floatingSelect">Cliente</label>
                </div>
            
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" name="fechaPedido" id="fechaPedido" placeholder="example" required>
                    <label for="fechaPedido">Fecha del Pedido:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="number" class="form-control" name="total" id="total" placeholder="example"
                        required>
                    <label for="total">Total del Pedido:</label>
                </div>
            
            
                <div class="form-floating  mb-3">
                    <select class="form-select" id="pago" aria-label="Floating label select example">
                        <option value="">elija el estado:</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Enviado">Entrega</option>
                        <option value="Cancelado">Cancelado</option>
                        <option value="Recibido">Recibido</option>
                    </select>
                    <label for="pago">Estado del Pedido:</label>
                </div>
            </div>


            <div class="col-5"> 
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="example"
                        required>
                    <label for="nombre">Nombre</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="direccion" id="direccion" placeholder="example"
                        required>
                    <label for="direccion">Direccion</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="example"
                        required>
                    <label for="telefono">Telefono</label>
                </div>
                 
                <div class="form-floating  mb-3">
                    <select class="form-select" id="pago" aria-label="Floating label select example">
                        <option value="">elija forma de pago:</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="ContraEntrega">Contra Entrega</option>
                        <option value="Consignacion">Consignacion</option>
                    </select>
                    <label for="pago">Forma de pago:</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" name="fechaEnvio" id="fechaEnvio" placeholder="example" required>
                    <label for="fechaEnvio">Fecha de Envio:</label>
                </div>

            </div>
        </div>
        <div class="d-flex justify-content-center mb-3">
            <button type="button" class="btn btn-primary" onclick="created()">
                <i class="fa fa-solid fa-square-plus fa-xl"></i>
                &nbsp Agregar
            </button>
        </form>
    </div>

    <div class="row justify-content-center">
        <h2 class="text-center bg-dark text-white p-1">Datos De Los Usuarios</h2>
        <div class="col-11 mx-3">
            <table class="table table-dark" id="tablePEd">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Cod Pedido</th>
                        <th scope="col">Fecha Pedido</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Direccion</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Total Pedido</th>
                        <th scope="col">Forma de PAgo</th>
                        <th scope="col">Fecha Envio</th>
                        <th scope="col">Estado Pedido</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody">

                </tbody>
            </table>
        </div>
    </div>

    <!-- Este es el Div para el modal -->
    <div class="">
        <!-- Modal -->
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateModal">Editar Usuario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="contenidoUpdate">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" onclick="update()" class="btn btn-warning"
                            data-bs-dismiss="modal">Modificar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
//* body
include_once "footer.php";
?>

<script src="assets/js/pedidos.js"></script>