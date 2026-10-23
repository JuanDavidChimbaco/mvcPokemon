<?php

include_once "header.php";
//* body
?>
<form id="comprasForm">
    <div class="row">
        <h2 class="text-center bg-dark text-white">Compras</h2>
    </div>

    <div class="row justify-content-center ">
        <h2 class="text-center bg-dark text-white p-2">Datos De la Compra</h2>
        <div class="col-8">
            <table class="table table-dark" id="tableProducts">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Codigo de Compra</th>
                        <th scope="col">Fecha de Compra</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Producto</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Valor de la compra</th>
                    </tr>
                </thead>
                <tbody id="tablePro">

                </tbody>
            </table>
        </div>
    </div>
    
</form>
<?php
//* body
include_once "footer.php";
?>

<script src="assets/js/compras.js"></script>