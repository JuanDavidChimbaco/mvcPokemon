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
        <div class="row text-center">
            <h1>LOG IN</h1>
        </div>
        <div class="row d-grid">
            <div class="col">
                <label for="" class="form-label">Username - Email</label>
                <input type="email" name="txtCorreo" id="txtCorreo" class="form-control">
            </div>
            <div class="col">
                <label for="" class="form-label">Password</label>
                <input type="password" name="txtPassword" id="txtPassword" class="form-control">
            </div>
            <div class="col mt-4 text-center">
                <button type="button" class="btn btn-primary" onclick="login()">Sign In</button>
            </div>
        </div>
        </dvi>
        <script src="./assets/js/login.js"></script>
</body>

</html>