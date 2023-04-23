<?php
include_once "header.php";
// body
?>
<div class="row mb-3">
    <h2 class="text-center bg-dark text-white">Roles</h2>
    <div class="row d-flex justify-content-center">
        <div class="col-8">
            <form class="form-floating">
                <input type="text" class="form-control" id="floatingInputValue" placeholder="Nombre Rol"
                    value="Usuario">
                <label for="floatingInputValue">Ingrese Rol</label>
            </form>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-8 d-flex justify-content-center">
                <input type="button" value="Crear" class="btn btn-success">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <h2 class="text-center bg-dark text-white">Roles</h2>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Handle</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>Jacob</td>
                <td>Thornton</td>
                <td>@fat</td>
            </tr>
            <tr>
                <th scope="row">3</th>
                <td colspan="2">Larry the Bird</td>
                <td>@twitter</td>
            </tr>
        </tbody>
    </table>
</div>

<?php
// body
include_once "footer.php";
?>
<!-- Incluimos el archivos roles .js -->
<script src="../js/roles.js"></script>
