<?php

include_once "header.php";
//* body
?>
<form id="usuariosForm">
    <div class="row">
        <h2 class="text-center bg-dark text-white">Usuarios</h2>
    </div>

    <div class="row justify-content-center my-1">
        <form>
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="TipoDoc" id="txtTipoDoc" placeholder="example"
                        required>
                    <label for="TipoDoc">Tipo de Documento:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" name="identificacion" id="txtIdentificacion"
                        placeholder="example" required>
                    <label for="identificacion">Numero de Documento:</label>
                </div>
            
            
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="nombre" id="txtNombre" placeholder="example" required>
                    <label for="nombre">Nombre del Usuario:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="apellido" id="txtApellido" placeholder="example"
                        required>
                    <label for="apellido">Apellido del Usuario:</label>
                </div>
            
            
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" name="correo" id="txtCorreo" placeholder="example"
                        required>
                    <label for="correo">Correo del Usuario:</label>
                </div>
            </div>
            <div class="col"> 
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="pass" id="txtPassword" placeholder="example"
                        required>
                    <label for="pass">Contraseña para el Usuario</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="direccion" id="txtDireccion" placeholder="example"
                        required>
                    <label for="direccion">Direccion del Usuario:</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="telefono" id="txtTelefono" placeholder="example"
                        required>
                    <label for="telefono">Telefono del Usuario</label>
                </div>

                <label for="" class="mb-3">Genero:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="genero" id="masculino" value="M">
                    <label class="form-check-label" for="genero">
                        Masculino
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="genero" id="femenino" value="F">
                    <label class="form-check-label" for="genero">
                        Femenino
                    </label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                        
                    </select>
                    <label for="floatingSelect">Rol</label>
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
        <div class="col-12">
            <table class="table table-dark" id="tableUsers">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tipo Doc</th>
                        <th scope="col">Id</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Direccion</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Genero</th>
                        <th scope="col">Rol</th>
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

<script src="assets/js/usuarios.js"></script>