<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets_Front/img/Squirtle.png">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/6bf421ec3d.js" crossorigin="anonymous"></script>

    <!-- sweetAlert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Regitro De Compra</title>
</head>

<body onload="mostrarDatosP()">
    <div class="row bg-body-secondary">
        <div class="col-5 d-flex justify-content-start m-1">
            <button type="button" class="btn btn-primary" onclick="retorno()">
                <i class="fas fa-solid fa-house"></i>
                Home
            </button>
        </div>
        <div class="col-6 d-flex justify-content-end m-1">
            <button type="button" class="btn btn-danger" onclick="logout()">
            <i class="fas fa-solid fa-right-from-bracket"></i>
             Log out</button>
        </div>

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
            <button type="button" class="btn btn-success" onclick="confirmShop()">Realizar Compra</button>
        </div>
    </div>
    <script src="assets_Front/js/compra.js"></script>
    <script src="../assets/js/logout.js"></script>
</body>

</html>