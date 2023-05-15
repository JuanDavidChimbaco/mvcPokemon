function created() {
    url = "../controllers/roles.create.php"

    //* Informacion del formulario
    var data = `nameRol=${document.getElementById("nameRol").value}`

    //* Opciones de la peticion
    var options = {
        method: 'POST',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch(url, options)
        .then(response => response.json())
        .then(data => {
            console.log(data)
            read()
            document.getElementById('nameRol').value = "";
        })
        .catch(error => {
            console.error(`Error al crear el rol: ${error}`);
        })
}

function read() {
    fetch("../controllers/roles.read.php")
        .then(response => response.json())
        .then(data => {
            console.log(data)
            let table = ""
            data.forEach((Element, index) => {
                table += `<tr>
                        <th scope='row'>${index + 1}</th>
                        <td>${Element.nombreRol}</td>
                        <td>${Element.estado}</td>
                        <td>${Element.fechaCreacion}</td>
                        <td>
                            <button type="button" onclick="readID(${Element.id})" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal">
                                Editar
                            </button>
                            <a class='btn btn-danger' onclick="deleteById(${Element.id})">Eliminar</a>
                        </td>
                    </tr>`
            });
            document.getElementById('tableRol').innerHTML = table;
        })
        .catch(error => {
            console.log("Error al consultar",error);
        });
}

function update() {
    //* Informacion del formulario
    var nombreRol = document.getElementById("nombreRol").value
    var estado = document.getElementById("estado").value
    var id = document.getElementById("idRol").value

    var data = {
        rol: nombreRol,
        estado: estado,
        id: id
    };

    //* Opciones de la peticion
    var options = {
        method: 'POST',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        }
    }

    fetch("../controllers/roles.update.php",options)
    .then(response => response.text())
    .then(data => {
        console.log(data);
        read();
    })
    .catch(error=>{
        console.error('Error:',error)
    });
}

function readID(id) {
    fetch("../controllers/roles.readId.php?id=" + id)
        .then(response => response.json())
        .then(data => {
            console.log(data)
            let datos = ''
                datos = `
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-6">
                            <form>
                                <div class="form-group">
                                    <label for="nombreRol">Nombre del Rol: </label>
                                    <input type="text" class="form-control" id="nombreRol" name="nombreRol" value="${data.nombreRol}"> 
                                </div>
                                <div class="form-group">
                                    <label for="estado">Estado:</label>
                                    <input type="text" class="form-control" id="estado" name="estado" value="${data.estado}">
                                </div>
                                <button type="button" class="btn btn-primary" onclick="update()">Save</button>
                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="idRol" name="idRol" value="${data.id}">
                                </div>
                             </form>
                        </div>
                    </div>
                </div>
                `
            
            document.getElementById('contenidoUpdate').innerHTML = datos;
        });
}

function deleteById(id) {
    // Opciones de la petición
    var options = {
        method: 'POST',
        body: JSON.stringify({ id: id }),
        headers: {
            'Content-Type': 'application/json'
        }
    };

    fetch("../controllers/roles.delete.php?id="+id, options)
        .then(response => response.text())
        .then(data => {
            console.log(data);
            read();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
window.onload = (event) => {
    read();
};
