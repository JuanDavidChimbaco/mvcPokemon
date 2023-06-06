// Funcion para validar el Login
function read(){
    fetch("../controllers/login.validate.php")
    .then(response => response.json())
    .then(data =>{
        console.log(data);
        if (data == false){
            window.location.href = "login.php";
        }
    })
}

// ejecutar la funcion  
read();