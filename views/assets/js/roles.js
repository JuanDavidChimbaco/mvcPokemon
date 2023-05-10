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
                            <a class='btn btn-danger' data-bs-toggle="modal" data-bs-target="#updateModal">Eliminar</a>
                        </td>
                    </tr>`
            });
            document.getElementById('tableRol').innerHTML = table;
        })
        .catch(error => {
            console.log("Error al consultar");
        });
}

function update() {
    //* Informacion del formulario
    var rol = `nameRol=${document.getElementById("nombreRol").value}`
    var estado = `estadoRol=${document.getElementById("estado").value}`

    //* Opciones de la peticion
    var options = {
        method: 'POST',
        body: `${rol}&${estado}`,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch("../controllers/roles.update.php",options)
    .then(response => response.json())
    .then(data => {
        console.log(data);
        read();
    })
}

function deletes() {

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
                        <div class="col-md-6">
                            <form>
                                <div class="form-group">
                                    <label for="nombreRol">Nombre del Rol:</label>
                                    <input type="text" class="form-control" id="nombreRol" name="nombreRol" value="${data.nombreRol}"> 
                                </div>
                                <div class="form-group">
                                    <label for="estado">Estado:</label>
                                    <input type="text" class="form-control" id="estado" name="estado" value="${data.estado}">
                                </div>
                                <button type="button" class="btn btn-primary" onclick="update()">Save changes</button>
                             </form>
                        </div>
                    </div>
                </div>
                `
            
            document.getElementById('contenidoUpdate').innerHTML = datos;
        });
}

window.onload = (event) => {
    read();
};
