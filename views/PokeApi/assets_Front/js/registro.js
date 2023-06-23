async function registro() {
    try {
        let tipoDocumento = document.getElementById('tipoD').value;
        let identificacion = document.getElementById('id').value;
        let nombre = document.getElementById('nombre').value;
        let apellido = document.getElementById('apellido').value;
        let correo = document.getElementById('correo').value;
        let direccion = document.getElementById('direcc').value;
        let telefono = document.getElementById('tel').value;
        let genero = document.getElementById('gen').value;
        let contrasena = document.getElementById('contrasena').value;
        let contrasena2 = document.getElementById('contrasena2').value;

        if (contrasena != contrasena2) {
            alert('No coinciden las contraseñas');
            document.getElementById('contrasena2').value = ""
            document.getElementById('contrasena').value = ""
        } else {

            let usuario = {
                tipoDocumento: tipoDocumento,
                identificacion: identificacion,
                nombre: nombre,
                apellido: apellido,
                correo: correo,
                direccion: direccion,
                telefono: telefono,
                genero: genero,
                contrasena: contrasena,
                idRol: 2
            };
            console.log(usuario);

            let opciones = {
                method: 'POST',
                body: JSON.stringify(usuario),
                headers: {
                    'Content-Type': 'application/json'
                }
            }
            const response = await fetch('../../controllers/user.register.php', opciones)
            const data = await response.json()
            console.log(data);
            if (data.result === 'Usuario Creado') {
                $('#successModal').modal('show');
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
}