let generoSeleccionado = "";

function guardarGenero() {
    const generoMasculino = document.getElementById("masculino");
    const generoFemenino = document.getElementById("femenino");

    if (generoMasculino.checked) {
        generoSeleccionado = generoMasculino.value;
    } else if (generoFemenino.checked) {
        generoSeleccionado = generoFemenino.value;
    }

    console.log("Género seleccionado:", generoSeleccionado);
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

function read() {
    fetch("../controllers/usuarios.read.php")
        .then(response => response.json())
        .then(data => {
            console.log(data);
            let table = ""
            data.forEach((Element, index) => {
                fetch(`../controllers/roles.readId.php?id=${Element.idRol}`)
                    .then(response => response.json())
                    .then(rolData => {
                        table += `<tr>
                                <th scope='row'>${index + 1}</th>
                                <td>${Element.tipoDoc}</td>
                                <td>${Element.identificacion}</td>
                                <td>${Element.nombre}</td>
                                <td>${Element.apellido}</td>
                                <td>${Element.correo}</td>
                                <td>${Element.direccion}</td>
                                <td>${Element.telefono}</td>
                                <td>${Element.genero}</td>
                                <td>${rolData.nombreRol}</td>
                                <td>
                                    <div class="form-check form-switch">
                                    <input onclick="statusRol('${Element.id}','${Element.estado}')" class="form-check-input" type="checkbox" role="switch" id="Switch${Element.identificacion}">
                                    <label class="form-check-label" for="Switch${Element.identificacion}">${Element.estado == "A" ? "Activo" : "Inactivo"}</label>
                                    </div>
                                </td>
                                <td>
                                    <a onclick="readID(${Element.id})" data-bs-toggle="modal" data-bs-target="#updateModal" class="btn btn-warning">
                                        <i class="fa fa-edit text-dark"></i>
                                    </a>
                                    <a onclick="modal(${Element.id})" class="btn btn-danger">
                                        <i class="fa fa-trash text-dark"></i>
                                    </a>
                                </td>
                            </tr>`
                        // Actualizar la tabla con los datos obtenidos
                        document.getElementById('tableUser').innerHTML = table;
                    })
                    .catch(error => {
                        console.log("Error al obtener el nombre del rol", error);
                    });
            });

            // Inicializar la DataTable o actualizarla si ya existe
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
            })
            // Actualizar el estado del checkbox
            actualizarEstado();
        })
        .catch(error => {
            console.log("Error al consultar", error);
        });
}

function update() {
    //* Informacion del formulario
    var tippDoc = document.getElementById("txtTipoDoc").value
    var nombre = document.getElementById("txtNombre").value
    var identificacion = document.getElementById("txtIdentificacion").value
    var apellido = document.getElementById("txtApellido").value
    var correo = document.getElementById("txtCorreo").value
    var password = document.getElementById("txtPassword").value
    var direccion = document.getElementById("txtDireccion").value
    var telefono = document.getElementById("txtTelefono").value
    var genero = document.getElementById("txtGenero1").value
    var rol = document.getElementById("floatingSelect").value

    let idP = localStorage.idP // Obtener el id del producto del LocalStorage 

    let data2 = `id=${idP}&nombrePro=${nombre}&precioPro=${precio}&cantidadPro=${cantidad}&descripPro=${descripcion}`;

    // opciones de la peticion con string
    let options = {
        method: 'POST',
        body: data2,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch("../controllers/productos.update.php", options) // Aqui se puede usasr options 
        .then(response => response.text())
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
                            <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" value="${data.tipoDoc}" required> 
                        </div>
                        <div class="col">
                            <label for="nombreRol">Identificacion:</label>
                            <input type="text" class="form-control" id="precioProducto" name="precioProducto" value="${data.identificacion}" required> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="nombreRol">Nombre:</label>
                            <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" value="${data.nombre}" required> 
                        </div>
                        <div class="col">
                            <label for="nombreRol">Apellido:</label>
                            <input type="text" class="form-control" id="precioProducto" name="precioProducto" value="${data.apellido}" required> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="nombreRol">Correo:</label>
                            <input type="text" class="form-control" id="cantidadProducto" name="cantidadProducto" value="${data.correo}" required> 
                        </div>
                        <div class="col">
                            <label for="nombreRol">Telefono:</label>
                            <input type="text" class="form-control" id="descripProducto" name="descripProducto" value="${data.telefono}" required> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="nombreRol">Género:</label>
                            <div>
                                <input type="radio" id="generoMasculino" name="genero" value="M" ${data.genero === 'M' ? 'checked' : ''}>
                                <label for="generoMasculino">Masculino</label>
                            </div>
                            <div>
                                <input type="radio" id="generoFemenino" name="genero" value="F" ${data.genero === 'F' ? 'checked' : ''}>
                                <label for="generoFemenino">Femenino</label>
                            </div>
                        </div>
                        <div class="col"> 
                            <label for="nombreRol">Rol:</label>
                            <select class="form-control" id="rolUsuario" name="rolUsuario" required>
                                ${generateRoleOptions(rolesData, data.idRol)}
                            </select>
                        </div>
                    </div>
                </form>
                </div>
                `
                    // guardo el id en el local storage es mas seguro que con input hidden
                    localStorage.idP = data.id;
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

    fetch("../controllers/productos.delete.php?id=" + id, options)
        .then(response => response.text())
        .then(data => {
            console.log(data);
            read();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function statusRol(id, estado) {
    let data = `id=${id}&estado=${estado}`

    let options = {
        method: 'POST',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch("../controllers/productos.estado.php", options)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            read()
        })
}

function actualizarEstado() {
    let input = document.getElementById("tableUser").getElementsByClassName("form-check-input")
    let label = document.getElementById("tableUser").getElementsByClassName("form-check-label")

    for (let i = 0; i < input.length; i++) {
        if (label[i].innerHTML == 'Activo') {
            input[i].setAttribute("checked", "");
        }
    }
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
                'Su Producto ha sido Eliminado.',
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
                'Tu Producto esta seguro ... Por el momento :)',
                'error'
            )
        }
    })
}

window.onload = (event) => {
    read();
    readRol();
};
