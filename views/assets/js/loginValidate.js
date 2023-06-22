// Funcion para validar el Login
function read(){
    fetch("http://localhost/mvcPokemon/controllers/login.validate.php")
    .then(response => response.text())
    .then(data =>{
        console.log(data);
        if (data == false){
            window.location.href = "http://localhost/mvcPokemon/views/login.php";
        }
    })
}

// ejecutar la funcion  
read();