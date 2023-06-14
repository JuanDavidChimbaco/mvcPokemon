async function create() {
    let nombreImp = document.getElementById('txtNombreImp').value
    let porcentaje = document.getElementById('txtPorcentaje').value

    // const datos = `nombreImp=${nombreImp}&porcentaje=${porcentaje}`;

    if (nombreImp.trim() === "" && porcentaje.trim() === "") {
        alert("Campo vacío");
    } else {
        let data = {
            nombre: nombreImp,
            porcentaje: porcentaje,
        };

        const options = {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        }

        try {
            const response = await fetch("../controllers/impuestos.create.php", options);
            const data = await response.text();
            read();
            clean();
        } catch (error) {
            console.error(`Error al crear el Impuesto: ${error}`);
        }
    }
}

let dataTable = null;

async function read() {
    try {
        const response = await fetch("../controllers/impuestos.create.php");
        const data = await response.json();
        const datos = data.result2;
        let table = "";
        datos.forEach((imp, index) => {
            table += `
            <tr>
                <th scope='row'>${index + 1}</th>
                <td>${imp.nombreImp}</td>
                <td>${imp.porcentaje}%</td>
                <td>
                    <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" onclick="status('${imp.id}','${imp.estado}')" ${imp.estado == "A" ? "checked" : ""}>
                        <label class="form-check-label" for="flexSwitchCheckChecked">${imp.estado == "A" ? "Activo" : "Inactivo"}</label>
                    </div>
                </td>
                <td>${imp.fechaCreacion}</td>
                <td>
                    <a onclick="readID(${imp.id})" data-bs-toggle="modal" data-bs-target="#updateModal" class="btn btn-warning">
                        <i class="fa fa-edit text-dark"></i>
                    </a>
                    <a onclick="modal(${imp.id})" class="btn btn-danger">
                        <i class="fa fa-trash text-dark"></i>
                    </a>
                </td>
            </tr>`;
        });
        if (dataTable) {
            document.getElementById('tableImp').classList.add('transition-effect');
            await new Promise(resolve => setTimeout(resolve, 300));
            dataTable.destroy();
            document.getElementById('tableImp').innerHTML = table;
            dataTable = new DataTable('#tableImpuestos', {
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
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'bg-success'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv"></i>',
                        titleAttr: 'CSV',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'bg-warning'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'bg-danger'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'bg-primary'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'bg-secondary'
                    }
                ]
            });
            await new Promise(resolve => setTimeout(resolve, 100));
            document.getElementById('tableImp').classList.remove('transition-effect');
        } else {
            document.getElementById('tableImp').innerHTML = table;
            dataTable = new DataTable('#tableImpuestos', {
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
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'bg-success'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv"></i>',
                        titleAttr: 'CSV',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'bg-warning'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'bg-danger'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'bg-primary'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        },
                        className: 'bg-secondary'
                    }
                ]
            });
        }
    } catch (error) {
        console.log(error);
    }
}

function clean() {
    document.getElementById('txtNombreImp').value = "";
    document.getElementById('txtPorcentaje').value = "";
}

function update() {
    //* Informacion del formulario
    var nombreImp = document.getElementById("txtNombreImp").value
    var porcentaje = document.getElementById("txtPorcentaje").value 

    let idI = localStorage.idImp // Obtener el id del LocalStorage 

    var datos = {
        id:idI,
        nombreImp: nombreImp,
        porcentajeImp: porcentaje
    };

    let data2 = `nombreRol=${nombreImp}&id=${porcentaje}`;

    //* Opciones de la peticion por medio de json 
    var options = {
        method: 'POST',
        body: JSON.stringify(datos),
        headers: {
            'Content-Type': 'application/json'
        }
    }

    // opciones de la peticion con string
    let options2 = {
        method: 'POST',
        body: data2,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch("../controllers/impuestos.create.php", options) 
        .then(response => response.json())
        .then(data => {
            console.log(data);
            const result6 = data.result6;
            console.log(result6);
            read();
        })
        // .catch(error => {
        //     console.error('Error:', error)
        // });
}

function readID(idI) {
    fetch(`../controllers/impuestos.create.php?id=${idI}`)
        .then(response => response.json())
        .then(data => {
            const result4 = data.result4;
            let datos = ''
            datos = `
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-6">
                            <form>
                                <div class="form-group">
                                    <label for="nombreRol">Nombre del Impuesto: </label>
                                    <input type="text" class="form-control" id="txtNombreImp" name="nombreImp" value="${result4.nombreImp}" required> 
                                </div>
                                <div class="form-group">
                                    <label for="nombreRol">Porcentaje: </label>
                                    <input type="number" class="form-control" id="txtPorcentaje" name="porcentaje" value="${result4.porcentaje}" required> 
                                </div>
                             </form>
                        </div>
                    </div>
                </div>
                `
            localStorage.idImp = result4.id;
            document.getElementById('contenidoUpdate').innerHTML = datos;
        });
}

function deleteById(id) {
    // Opciones de la petición
    var options = {
        method: 'POST',
        body: JSON.stringify({ idD: id }),
        headers: {
            'Content-Type': 'application/json'
        }
    };

    fetch(`../controllers/impuestos.create.php?id=${id}`, options)
        .then(response => response.text())
        .then(data => {
            read();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

async function status(id, estado) {
    let data2 = {
        idE: id,
        estadoE: estado
    };

    let options = {
        method: 'POST',
        body: JSON.stringify(data2),
        headers: {
            'Content-Type': 'application/json'
        }
    };

    try {
        const response = await fetch("../controllers/impuestos.create.php", options);
        const data = await response.text();
        read();
    } catch (error) {
        console.error(error);
    }
}

function modal(idImp) {
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
            deleteById(idImp);
            swalWithBootstrapButtons.fire(
                'Eliminado!',
                'Su impuesto ha sido Eliminado.',
                'success'
            )
        } else if (
            /* cerrar el Modal si se cancela */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelado',
                'error'
            )
        }
    })
}

window.onload = (event) => {
    read();
};
