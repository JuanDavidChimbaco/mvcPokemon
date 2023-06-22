<?php

include_once "header.php";
//* body
?>

    <div class="row">
        <h1 class="text-center bg-dark text-white">Dashboard - Administrador</h1>
    </div>

    <div class="container pt-5">

        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <a href="productosForm.php" class="card text-center text-decoration-none">
                    <div class="card-body shadow">
                        <i class="fas fa-box-open fa-5x"></i>
                        <h5 class="card-title mt-3">Productos</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <a href="rolesForm.php" class="card text-center text-decoration-none">
                    <div class="card-body shadow">
                        <i class="fas fa-users fa-5x"></i>
                        <h5 class="card-title mt-3">Roles</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <a href="usuariosForm.php" class="card text-center text-decoration-none">
                    <div class="card-body shadow">
                        <i class="fas fa-user-friends fa-5x"></i>
                        <h5 class="card-title mt-3">Usuarios</h5>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
                <a href="impuestosForm.php" class="card text-center text-decoration-none">
                    <div class="card-body shadow">
                        <i class="fas fa-percent fa-5x"></i>
                        <h5 class="card-title mt-3">Impuestos</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
<?php
//* body
include_once "footer.php";
?>
