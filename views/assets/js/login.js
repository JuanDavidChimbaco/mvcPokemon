function login() {
    fetch(`../controllers/login.php?txtCorreo=${txtCorreo.value}&txtPassword=${txtPassword.value}`)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (data[0] != undefined) {
                if(data[0].idRol == 1){
                    window.location.href = "dashboard.php";
                }else{
                    window.location.href = "carrito.php"
                }
            } else {
                alert('Usuario/Correo o password incorrectos');
            }
        })
}