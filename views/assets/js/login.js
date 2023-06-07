function login() {
    fetch(`../controllers/login.php?txtCorreo=${txtCorreo.value}&txtPassword=${txtPassword.value}`)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (data[0] != undefined) {
                window.location.href = "rolesForm.php";
            } else {
                alert('Usuario o password incorrectos');
            }
        })
}