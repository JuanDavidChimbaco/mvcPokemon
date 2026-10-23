async function created() {
    url = "../controllers/pedidos.control.php"

    var codigoPedido = document.getElementById('pedido').value;
    var cliente = document.getElementById('floatingSelect').value;
    var fechaPedido = document.getElementById('fechaPedido').value;
    var totalPedido = document.getElementById('total').value;
    var estadoPedido = document.getElementById('pago').value;
    var nombre = document.getElementById('nombre').value;
    var direccion = document.getElementById('direccion').value;
    var telefono = document.getElementById('telefono').value;
    var formaPago = document.getElementById('pago').value;
    var fechaEnvio = document.getElementById('fechaEnvio').value;

    let action = 'create';
    let datos = `codigoPedido=${codigoPedido}&cliente=${cliente}&fechaPedido=${fechaPedido}&totalPedido=${totalPedido}&estadoPedido=${estadoPedido}&nombre=${nombre}&direccion=${direccion}&telefono=${telefono}&formaPago=${formaPago}&fechaEnvio=${fechaEnvio}&action=${action}`;

    let options = {
        method: 'POST',
        body: datos,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }
    try{
        const response = await fetch(url, options)
        const data = await response.text()
        console.log(data)
        read();
    }catch(error){
        console.error(`Error en la peticion: ${error}`);
    }
}

function limipiar() {
    document.getElementById('pedido').value=""
    document.getElementById('floatingSelect').value=""
    document.getElementById('fechaPedido').value=""
    document.getElementById('total').value=""
    document.getElementById('pago').value=""
    document.getElementById('nombre').value=""
    document.getElementById('direccion').value=""
    document.getElementById('telefono').value=""
    document.getElementById('pago').value=""
    document.getElementById('fechaEnvio').value=""
}
function readUser() {
    fetch("../controllers/usuarios.read.php")
        .then(response => response.json())
        .then(data => {
            let select = `<option selected value="">Seleccione un Usuario</option>`
            data.forEach((user) => {
                select += `<option value="${user.id}">${user.nombre}</option>`
            });
            document.getElementById('floatingSelect').innerHTML = select;
        })
        .catch(error => {
            console.log("Error al consultar", error);
        });
}

let dataTable = null;

async function read() {
    const response = await fetch("../controllers/pedidos.control.php");
    const data = await response.json();
    let table = "";
    console.log(data);
    for (const [index, pedido] of data['0'].result.entries()) {
        const nombreUser = await getNombreUser(pedido.idUsu);
        table += `  
            <tr>
                <th scope='row'>${index + 1}</th>
                <td>${pedido.codigoPed}</td>
                <td>${pedido.fechaPed}</td>
                <td>${nombreUser}</td>
                <td>${pedido.nombre}</td>
                <td>${pedido.direccion}</td>
                <td>${pedido.telefono}</td>
                <td>${pedido.totalPed}</td>
                <td>${pedido.formaPago}</td>
                <td>${pedido.fechaEnvPedido}</td>
                <td>${pedido.estadoPedido}</td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" onclick="status('${pedido.id}','${pedido.estado}')" ${pedido.estado == "A" ? "checked" : ""}>
                        <label class="form-check-label" for="flexSwitchCheckChecked">${pedido.estado == "A" ? "Activo" : "Inactivo"}</label>
                    </div>
                </td>
                <td>
                    <a onclick="readID(${pedido.id})" data-bs-toggle="modal" data-bs-target="#updateModal" class="btn btn-warning">
                        <i class="fa fa-edit text-dark"></i>
                    </a>
                    <a onclick="modal(${pedido.id})" class="btn btn-danger">
                        <i class="fa fa-trash text-dark"></i>
                    </a>
                </td>
             </tr> `

    }
    if (dataTable) {
        dataTable.destroy();
    }
    document.getElementById('tableBody').innerHTML = table;
    dataTable = new DataTable('#tablePEd', {
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

async function getNombreUser(id) {
    const response = await fetch("../controllers/usuarios.readId.php?id=" + id);
    const data = await response.json();
    return data.nombre;
}

function update() {
    //* Informacion del formulario
    var codigoPedido = document.getElementById('pedido2').value;
    var cliente = document.getElementById('floatingSelect2').value;
    var fechaPedido = document.getElementById('fechaPedido2').value;
    var totalPedido = document.getElementById('total2').value;
    var estadoPedido = document.getElementById('pago2').value;
    var nombre = document.getElementById('nombre2').value;
    var direccion = document.getElementById('direccion2').value;
    var telefono = document.getElementById('telefono2').value;
    var formaPago = document.getElementById('pago2').value;
    var fechaEnvio = document.getElementById('fechaEnvio2').value;

    let idU = localStorage.idU 

    let data2 = `id=${idU}&tipoDoc=${tipoDoc}&identificacion=${identificacion}&nombre=${nombre}&apellido=${apellido}&correo=${correo}&direccion=${direccion}&telefono=${telefono}&genero=${genero}&idRol=${rol}`;

    let options = {
        method: 'POST',
        body: data2,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch("../controllers/pedidos.control.php", options)
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
    fetch("../controllers/pedidos.control.php?id=" + id)
        .then(response => response.json())
        .then(data => {
            let datos = ''
            fetch(`../controllers/usuarios.read.php`)
                .then(response => response.json())
                .then(userData => {
                    datos = `
                    <div class="row justify-content-center my-1">
                    <form>
                        <div class="col-5">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="pedido" id="pedido" placeholder="example" value="${data.codigoPed}"
                                    required >
                                <label for="pedido">Codigo de Pedido:</label>
                            </div>
            
                            <div class="form-floating mb-3">
                                <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                ${enerateRoleOptions(userData, data.idUsu)}
                                </select>
                                <label for="floatingSelect">Cliente</label>
                            </div>
                        
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="fechaPedido" id="fechaPedido" placeholder="example" required>
                                <label for="fechaPedido">Fecha del Pedido:</label>
                            </div>
            
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="total" id="total" placeholder="example"
                                    required>
                                <label for="total">Total del Pedido:</label>
                            </div>
                        
                        
                            <div class="form-floating  mb-3">
                                <select class="form-select" id="pago" aria-label="Floating label select example">
                                    <option value="">elija el estado:</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Enviado">Entrega</option>
                                    <option value="Cancelado">Cancelado</option>
                                    <option value="Recibido">Recibido</option>
                                </select>
                                <label for="pago">Estado del Pedido:</label>
                            </div>
                        </div>
            
            
                        <div class="col-5"> 
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="example"
                                    required>
                                <label for="nombre">Nombre</label>
                            </div>
            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="direccion" id="direccion" placeholder="example"
                                    required>
                                <label for="direccion">Direccion</label>
                            </div>
            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="example"
                                    required>
                                <label for="telefono">Telefono</label>
                            </div>
                             
                            <div class="form-floating  mb-3">
                                <select class="form-select" id="pago" aria-label="Floating label select example">
                                    <option value="">elija forma de pago:</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="ContraEntrega">Contra Entrega</option>
                                    <option value="Consignacion">Consignacion</option>
                                </select>
                                <label for="pago">Forma de pago:</label>
                            </div>
            
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" name="fechaEnvio" id="fechaEnvio" placeholder="example" required>
                                <label for="fechaEnvio">Fecha de Envio:</label>
                            </div>
            
                        </div>
                    </div>
                `
                    // guardo el id en el local storage es mas seguro que con input hidden
                    localStorage.idU = data.id;
                    document.getElementById('contenidoUpdate').innerHTML = datos;
                });
        });
}

function generateRoleOptions(users, selectedUserId) {
    let options = '';
    users.forEach((user) => {
        const selected = user.id === selectedUserId ? 'selected' : '';
        options += `<option value="${user.id}" ${selected}>${user.nombre}</option>`;
    });
    return options;
}

function deleteById(id) {
    // Opciones de la petición
    var options = {
        method: 'POST',
        body: JSON.stringify({ id: id ,action:'delete'}),
        headers: {
            'Content-Type': 'application/json'
        }
    };

    fetch("../controllers/pedidos.control.php?id=" + id, options)
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
    let data = `id=${id}&estado=${estado}&action='estado'`

    let options = {
        method: 'POST',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch("../controllers/pedidos.control.php", options)
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
            )
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
    readUser();
};
