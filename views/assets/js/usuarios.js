let generoSeleccionado = "";

function guardarGenero() {
    const generoMasculino = document.getElementById("masculino");
    const generoFemenino = document.getElementById("femenino");

    if (generoMasculino.checked) {
        generoSeleccionado = generoMasculino.value;
    } else if (generoFemenino.checked) {
        generoSeleccionado = generoFemenino.value;
    }
    return generoSeleccionado
}

function guardarGeneroUpdate() {
    const generoMasculino = document.getElementById("radioMasculino");
    const generoFemenino = document.getElementById("radioFemenino");

    if (generoMasculino.checked) {
        generoSeleccionado = generoMasculino.value;
    } else if (generoFemenino.checked) {
        generoSeleccionado = generoFemenino.value;
    }
    return generoSeleccionado
}

function created() {
    url = "../controllers/usuarios.create.php"

    //* Informacion del formulario
    var tippDoc = document.getElementById("txtTipoDoc").value
    var nombre = document.getElementById("txtNombre").value
    var identificacion = document.getElementById("txtIdentificacion").value
    var apellido = document.getElementById("txtApellido").value
    var correo = document.getElementById("txtCorreo").value
    var password = document.getElementById("txtPassword").value
    var direccion = document.getElementById("txtDireccion").value
    var telefono = document.getElementById("txtTelefono").value
    var genero = guardarGenero()
    var rol = document.getElementById("floatingSelect").value

    let data2 = `tipoDoc=${tippDoc}&identificacion=${identificacion}&nombre=${nombre}&apellido=${apellido}&correo=${correo}&pass=${password}&direccion=${direccion}&telefono=${telefono}&genero=${genero}&idRol=${rol}`;

    let options2 = {
        method: 'POST',
        body: data2,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch(url, options2)
        .then(response => response.text())
        .then(data => {
            console.log(data)
            read();
        })
    location.reload()
        .catch(error => {
            console.error(`Error en la peticion: ${error}`);
        })
}

function limipiar() {
    document.getElementById('txtTipoDoc').value = "";
    document.getElementById("txtIdentificacion").value = "";
    document.getElementById("txtNombre").value = "";
    document.getElementById("txtApellido").value = "";
    document.getElementById("txtCorreo").value = "";
    document.getElementById("txtPassword").value = "";
    document.getElementById("txtDireccion").value = "";
    document.getElementById("txtTelefono").value = "";
    document.getElementById("txtGenero1").checkbox.checked = false;
    document.getElementById("txtGenero2").checkbox.checked = false;
    document.getElementById("cbIdRol").value = "0";
}

function readRol() {
    fetch("../controllers/roles.read.php")
        .then(response => response.json())
        .then(data => {
            let select = `<option selected value="">Seleccione un Rol</option>`
            data.forEach((rol) => {
                select += `<option value="${rol.id}">${rol.nombreRol}</option>`
            });
            document.getElementById('floatingSelect').innerHTML = select;
        })
        .catch(error => {
            console.log("Error al consultar", error);
        });
}

 async function read() {
    fetch("../controllers/usuarios.read.php")
    const response = await fetch("../controllers/usuarios.read.php");
    const data = await response.json();
    console.log(data);
    let table = "";
    for (const [index, usuario] of data.entries()) {
        const nombreRol = await getNombreRol(usuario.idRol);
                    table += `  <tr>
                                    <th scope='row'>${index + 1}</th>
                                    <td>${usuario.tipoDoc}</td>
                                    <td>${usuario.identificacion}</td>
                                    <td>${usuario.nombre}</td>
                                    <td>${usuario.apellido}</td>
                                    <td>${usuario.correo}</td>
                                    <td>${usuario.direccion}</td>
                                    <td>${usuario.telefono}</td>
                                    <td>${usuario.genero}</td>
                                    <td>${nombreRol}</td>
                                    <td>
                                    <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" onclick="status('${usuario.id}','${usuario.estado}')" ${usuario.estado == "A" ? "checked" : ""}>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">${usuario.estado == "A" ? "Activo" : "Inactivo"}</label>
                                  </div>
                                    </td>
                                    <td>
                                        <a onclick="readID(${usuario.id})" data-bs-toggle="modal" data-bs-target="#updateModal" class="btn btn-warning">
                                            <i class="fa fa-edit text-dark"></i>
                                        </a>
                                        <a onclick="modal(${usuario.id})" class="btn btn-danger">
                                            <i class="fa fa-trash text-dark"></i>
                                        </a>
                                    </td>
                                </tr>`

    }
    // Actualizar la tabla con los datos obtenidos
    document.getElementById('tableBody').innerHTML = table;
    let tables = new DataTable('#tableUsers', {
                // Configuración de DataTables...
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-MX.json'
                },
                retrieve: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i>',
                        titleAttr: 'Copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        },
                        className: 'bg-success'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv"></i>',
                        titleAttr: 'CSV',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        },
                        className: 'bg-warning'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        },
                        className: 'bg-danger'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        },
                        className: 'bg-primary'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        },
                        className: 'bg-secondary'
                    }
                ]
    });
}

async function getNombreRol(id) {
    const response = await fetch("../controllers/roles.readId.php?id=" + id);
    const data = await response.json();
    return data.nombreRol;
}

function update() {
    //* Informacion del formulario
    var tipoDoc = document.getElementById("TipoDoc").value
    var identificacion = document.getElementById("identificacion").value
    var nombre = document.getElementById("nombre").value
    var apellido = document.getElementById("apellido").value
    var correo = document.getElementById("correo").value
    var direccion = document.getElementById("direccion").value
    var telefono = document.getElementById("telefono").value
    var genero = guardarGeneroUpdate()
    var rol = document.getElementById("rolUsuario").value

    let idU = localStorage.idP // Obtener el id del producto del LocalStorage 

    let data2 = `id=${idU}&tipoDoc=${tipoDoc}&identificacion=${identificacion}&nombre=${nombre}&apellido=${apellido}&correo=${correo}&direccion=${direccion}&telefono=${telefono}&genero=${genero}&idRol=${rol}`;

    // opciones de la peticion con string
    let options = {
        method: 'POST',
        body: data2,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch("../controllers/usuarios.update.php", options) // Aqui se puede usasr options 
        .then(response => response.json())
        .then(data => {
            console.log(data);
            read();
        })
        .catch(error => {
            console.error('Error:', error)
        });
}

function readID(id) {
    fetch("../controllers/usuarios.readId.php?id=" + id)
        .then(response => response.json())
        .then(data => {
            console.log(data)
            let datos = ''
            fetch(`../controllers/roles.read.php`)
                .then(response => response.json())
                .then(rolesData => {
                    console.log(rolesData);
                    datos = `
                <div class="container">
                <form>
                    <div class="row">
                        <div class="col">
                            <label for="nombreRol">Tipo Documento:</label>
                            <input type="text" class="form-control" id="TipoDoc" name="TipoDoc" value="${data.tipoDoc}" required> 
                        </div>
                        <div class="col">
                            <label for="nombreRol">Identificacion:</label>
                            <input type="number" class="form-control" id="identificacion" name="identificacion" value="${data.identificacion}" required> 
                        </div>
                        <div class="col"> 
                            <label for="nombreRol">Rol:</label>
                            <select class="form-control" id="rolUsuario" name="rolUsuario" required>
                                ${generateRoleOptions(rolesData, data.idRol)}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="nombreRol">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="${data.nombre}" required> 
                        </div>
                        <div class="col">
                            <label for="nombreRol">Apellido:</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" value="${data.apellido}" required> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="nombreRol">Correo:</label>
                            <input type="text" class="form-control" id="correo" name="correo" value="${data.correo}" required> 
                        </div>
                        <div class="col">
                            <label for="nombreRol">Telefono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="${data.telefono}" required> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="nombreRol">Género:</label>
                            <div>
                                <input type="radio" id="radioMasculino" name="genero" value="M" ${data.genero === 'M' ? 'checked' : ''} >
                                <label for="masculino">Masculino</label>
                            </div>
                            <div>
                                <input type="radio" id="radioFemenino" name="genero" value="F" ${data.genero === 'F' ? 'checked' : ''}>
                                <label for="femenino">Femenino</label>
                            </div>
                        </div>
                        <div class="col">
                            <label for="nombreRol">Direccion:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="${data.direccion}" required> 
                        </div>
                    </div>
                </form>
                </div>
                `
                    // guardo el id en el local storage es mas seguro que con input hidden
                    localStorage.idU = data.id;
                    document.getElementById('contenidoUpdate').innerHTML = datos;
                });
        });
}

function generateRoleOptions(roles, selectedRoleId) {
    let options = '';
    roles.forEach((role) => {
        const selected = role.id === selectedRoleId ? 'selected' : '';
        options += `<option value="${role.id}" ${selected}>${role.nombreRol}</option>`;
    });
    return options;
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

    fetch("../controllers/usuarios.delete.php?id=" + id, options)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            read();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function status(id, estado) {
    let data = `id=${id}&estado=${estado}`

    let options = {
        method: 'POST',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch("../controllers/usuarios.estado.php", options)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            read()
        })
}

function modal(idPro) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Estas Seguro?',
        text: "No podras Revertir Esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'SI, Eliminar!',
        cancelButtonText: 'No, Cancelar!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            deleteById(idPro)
            swalWithBootstrapButtons.fire(
                'Eliminado!',
                'Usuario Eliminado.',
                'success'
            ).then(() => {
                location.reload();
            });
        } else if (
            /* cerrar el Modal si se cancela */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelado',
                'El Usuario esta seguro ... Por el momento :)',
                'error'
            )
        }
    })
}

window.onload = (event) => {
    read();
    readRol();
};
