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
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('assets_Front/img/bg.jpg');
            background-size: cover;
            background-position: center;
        }

        .container {
            position: relative;
            z-index: 1;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.8);
            width: 70%;
        }
    </style>
    <title>Registro</title>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h5 class="card-title">Registro de Usuario</h5>
                </div>
                <form method="POST">
                    <div class="row">
                        <div class="col form-group">
                            <label for="tipoD">Tipo de documento</label>
                            <input type="text" class="form-control" id="tipoD" name="tipoD"
                                placeholder="Ingrese su Tipo de documento" required>
                        </div>
                        <div class="col form-group">
                            <label for="id">Identificacion</label>
                            <input type="number" class="form-control" id="id" name="id"
                                placeholder="Ingrese su identificacion" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                placeholder="Ingrese su nombre" required>
                        </div>
                        <div class="col form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido"
                                placeholder="Ingrese su apellido" required>
                        </div>
                        <div class="col form-group">
                            <label for="correo">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo"
                                placeholder="Ingrese su correo electrónico" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col form-group">
                            <label for="direcc">Direccion</label>
                            <input type="text" class="form-control" id="direcc" name="direcc"
                                placeholder="Ingrese su direccion" required>
                        </div>
                        <div class="col form-group">
                            <label for="tel">Telefono</label>
                            <input type="text" class="form-control" id="tel" name="tel"
                                placeholder="Ingrese su telefono" required>
                        </div>
                        <div class="col form-group">
                            <label for="gen">Género</label>
                            <select class="form-control" id="gen" name="gen" required>
                                <option value="" selected disabled>Seleccione su género</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena"
                            placeholder="Ingrese su contraseña" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Confirme Contraseña</label>
                        <input type="password" class="form-control" id="contrasena2" name="contrasena2"
                            placeholder="Ingrese su contraseña" required>
                    </div>
                    <div class="text-center pt-3">
                        <button type="button" class="btn btn-primary" onclick="registro()">Registrarse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Usuario registrado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>El usuario ha sido registrado exitosamente.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="redirectButton">Continuar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('redirectButton').addEventListener('click', function () {
            window.location.href = 'index.php';
        });
    </script>
    <script src="assets_Front/js/registro.js"></script>
</body>

</html>