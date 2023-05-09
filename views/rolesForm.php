<?php

include_once "header.php";
//* body
?>
<form id="rolesForm">
    <div class="row">
        <h2 class="text-center bg-dark text-white">Roles</h2>
    </div>

    <div class="row justify-content-center my-1">
        <div class="col-6">
            <form>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="nameRol" id="nameRol" placeholder="example">
                    <label for="nameRol">Nombre Del Rol:</label>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-primary" onclick="created()">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row justify-content-center ">
        <h2 class="text-center bg-dark text-white p-2">Datos De Roles</h2>
        <div class="col-8">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NombreRol</th>
                        <th scope="col">Estado</th>
                        <th scope="col">FechaCreacion</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody id="tableRol">

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
                        <h1 class="modal-title fs-5" id="updateModal">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
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

<script src="assets/js/roles.js"></script>