// Funcion para validar el Login
async function read(){
    const response = await fetch("../controllers/login.validate.php")
    const data = await response.json()
    console.log(data);
        if (data == false){
            window.location.href = "login.php";
        }
}

// ejecutar la funcion  
read();