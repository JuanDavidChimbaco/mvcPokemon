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

    <title>Log In</title>
</head>

<body>
    <div class="container">
        <div class="row text-center pt-5">
            <h1>LOG IN</h1>
        </div>
        <div class="row d-grid justify-content-center pt-5">
            <div class="col">
                <label for="" class="form-label">Username - Email</label>
                <input type="email" name="txtCorreo" id="txtCorreo" class="form-control">
            </div>
            <div class="col">
                <label for="" class="form-label">Password</label>
                <input type="password" name="txtPassword" id="txtPassword" class="form-control">
            </div>
            <div class="col mt-4 text-center pb-5">
                <button type="button" class="btn btn-primary" onclick="login()">Sign In</button>
            </div>
        </div>
        <div class="row text-center d-grid pt-4">
            <div class="col">
                <p class="form-text">
                    No tienes Cuenta ?
                </p>
            </div>
            <div class="col">
                <a href="" class="btn btn-info"> Registrese Aqui </a>
            </div>
        </div>
        </dvi>
        <script src="./assets/js/login.js"></script>
</body>

</html>