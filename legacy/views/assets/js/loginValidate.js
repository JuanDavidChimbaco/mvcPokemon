// Funcion para validar el Login
async function read(){
    const response = await fetch("../controllers/login.validate.php")
    const data = await response.json()
    console.log(data);
        if (data == false){
            window.location.href = "login.php";
        }else{
            const response2 = await fetch('../controllers/login.restricted.php')
            const data2 = await response2.text()
            console.log(data2);
            if (data2 == 'false'){
                window.location.href = "error.php";  
            }
        }
}

// ejecutar la funcion  
read();
