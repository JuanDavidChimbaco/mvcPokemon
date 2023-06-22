async function registro(){
    let nombre = document.getElementById('nombre').value ;
    let apellido = document.getElementById('apellido').value ;
    let correo = document.getElementById('correo').value ;
    let contrasena = document.getElementById('contrasena').value ;
    let contrasena2 = document.getElementById('contrasena2').value ;

    if(contrasena != contrasena2){
        alert('No coinciden las contraseñas');
        document.getElementById('contrasena2').value = ""
        document.getElementById('contrasena').value = ""
    }

    let usuario = {
        nombre:nombre,
        apellido:apellido,
        correo:correo,
        contrasena:contrasena
    }

    opciones = {
        method: 'POST',
        body:JSON.stringify(usuario),
        headers: {
            'Content-Type':'application/json'
        }
    }
    const response = await fetch('')
    const data = await response.json()
    console.log(data);
}