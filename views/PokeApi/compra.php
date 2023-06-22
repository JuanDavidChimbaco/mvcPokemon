<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets_Front/img/Squirtle.png">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <title>Regitro De Compra</title>
</head>

<body onload="mostrarDatosP()">
<div class="d-flex justify-content-end">
    <button type="button" class="btn btn-danger" onclick="logout()"> Log out</button>
</div>
    <div class="container">
        <div class="row">
            <h2>Bienvenido Su Compra Casi esta lista.</h2>
        </div>
        <div class="row">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">SubTotal</th>
                    </tr>
                </thead>
                <tbody id="tabla-container" class="table-secondary">
                    
                </tbody>
            </table>
        </div>

        <div class="row">
            <button type="button" class="btn btn-success" onclick="realizarCompra()">Realizar Compra</button>
        </div>
    </div>
    <script src="assets_Front/js/compra.js"></script>
    <script src="assets_Front/js/loginValidate.js"></script>
    <script src="../assets/js/logout.js"></script>
</body>

</html>