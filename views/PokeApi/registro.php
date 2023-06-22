<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../assets/img/Knight.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N"
        crossorigin="anonymous"></script>
    <title>Registro</title>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card" style="width: 90%;">
            <div class="card-body">
                <div class="text-center">
                    <h5 class="card-title">Registro de Usuario</h5>
                </div>
                <form method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese su apellido" required>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese su correo electrónico" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Confirme Contraseña</label>
                        <input type="password" class="form-control" id="contrasena2" name="contrasena2" placeholder="Ingrese su contraseña" required>
                    </div>
                    <div class="text-center pt-3">
                        <button type="submit" class="btn btn-primary" onclick="registro()">Registrarse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="assets_Front/js/registro.js"></script>
</body>
</html>