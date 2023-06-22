<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="assets/img/Knight.png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- boostrap 5.3  -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="./assets/js/bootstrap.min.js"></script>

    <!-- Hoja de estilos local -->
    <link rel="stylesheet" href="assets/css/stylesLogin.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,600;1,100&family=Playfair+Display:ital,wght@0,400;0,600;1,400;1,600&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <title>Log In</title>

</head>

<body>
    <div class="background-container">
        <div class="card-container d-grid">
            <div class="card">
                <h1 class="text-center titulo">LOG IN</h1>
                <div class="mb-3">
                    <label for="txtCorreo" class="form-label">Username - Email</label>
                    <input type="email" name="txtCorreo" id="txtCorreo" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="txtPassword" class="form-label">Password</label>
                    <input type="password" name="txtPassword" id="txtPassword" class="form-control">
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-primary" onclick="login()">Sign In</button>
                </div>
                
            </div>
            <div>
                <label for="">No tiene Cuenta ?</label>
                <a href="./PokeApi/registro.php" class="link-dark">Registrese Aqui</a>
            </div>
        </div>
    </div>
    <script src="./assets/js/login.js"></script>
</body>

</html>