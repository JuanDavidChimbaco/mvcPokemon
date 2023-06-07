function created() {
    url = "../controllers/productos.create.php"

    //* Informacion del formulario
    var nombre = document.getElementById("txtNombre").value
    var precio = document.getElementById("txtPrecio").value
    var cantidad = document.getElementById("txtCantidad").value
    var descripcion = document.getElementById("txtDescripcion").value

    let data2 = `nombrePro=${nombre}&precioPro=${precio}&cantidadPro=${cantidad}&descripPro=${descripcion}`;

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
                document.getElementById('txtNombre').value = "";
                document.getElementById("txtPrecio").value = "";
                document.getElementById("txtCantidad").value = "";
                document.getElementById("txtDescripcion").value = "";
            })
            location.reload()
            .catch(error => {
                console.error(`Error en la peticion: ${error}`);
            })
    

}

function read() {
    fetch("../controllers/productos.read.php")
        .then(response => response.json())
        .then(data => {
            console.log(data);
            let table = ""
            data.forEach((Element, index) => {
                table += `<tr>
                        <th scope='row'>${index + 1}</th>
                        <td>${Element.nombrePro}</td>
                        <td>${Element.precioPro}</td>
                        <td>${Element.cantidadPro}</td>
                        <td>${Element.descripPro}</td>
                        <td>
                            <div class="form-check form-switch">
                            <input onclick="statusRol('${Element.id}','${Element.estado}')" class="form-check-input" type="checkbox" role="switch" id="Switch${Element.nombrePro}">
                            <!--"${Element.estado == "A" ? "checked" : ""}-->
                            <label class="form-check-label" for="Switch${Element.nombreRol}">${Element.estado == "A" ? "Activo" : "Inactivo"}</label>
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
            });
            document.getElementById('tablePro').innerHTML = table;
            let tables = new DataTable('#tableProducts',{
                language: {
                    url:'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-MX.json'
                },
                retrieve: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend:'copy',
                        text: '<i class="fa fa-copy"></i>',
                        titleAttr: 'Copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                          },
                          className: 'bg-success'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv"></i>',
                        titleAttr: 'CSV',
                        exportOptions: {
                          columns: [0, 1, 2, 3, 4, 5]
                        },
                        className: 'bg-warning'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        exportOptions: {
                          columns: [0, 1, 2, 3, 4, 5]
                        },
                        className: 'bg-danger'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        exportOptions: {
                          columns: [0, 1, 2, 3, 4, 5]
                        },
                        className: 'bg-primary'
                    },
                    {
                        extend:'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Print',
                        exportOptions:{
                            columns: [0, 1, 2, 3, 4, 5]
                        },
                        className: 'bg-secondary'
                    }
                ]
            })
            actualizarEstado();
        })
        .catch(error => {
            console.log("Error al consultar",error);
        });
}

function update() {
    //* Informacion del formulario
    var nombre = document.getElementById("nombreProducto").value
    var precio = document.getElementById("precioProducto").value
    var cantidad = document.getElementById("cantidadProducto").value
    var descripcion = document.getElementById("descripProducto").value

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

    fetch("../controllers/productos.update.php",options) // Aqui se puede usasr options 
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
    fetch("../controllers/productos.readId.php?id="+id)
        .then(response => response.json())
        .then(data => {
            console.log(data)
            let datos = ''
                datos = `
                <div class="container">
                <form>
                    <div class="row">
                        <div class="col">
                            <label for="nombreRol">Nombre del Producto:</label>
                            <input type="text" class="form-control" id="nombreProducto" name="nombreProducto" value="${data.nombrePro}" required> 
                        </div>
                        <div class="col">
                            <label for="nombreRol">Precio del Producto:</label>
                            <input type="number" class="form-control" id="precioProducto" name="precioProducto" value="${data.precioPro}" required> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="nombreRol">Cantidad del Producto:</label>
                            <input type="number" class="form-control" id="cantidadProducto" name="cantidadProducto" value="${data.cantidadPro}" required> 
                        </div>
                        <div class="col">
                            <label for="nombreRol">Descripcion del Producto:</label>
                            <input type="text" class="form-control" id="descripProducto" name="descripProducto" value="${data.descripPro}" required> 
                        </div>
                    </div>
                </form>
                </div>
                `
            // guardo el id en el local storage es mas seguro que con input hidden
            localStorage.idP = data.id;
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

    fetch("../controllers/productos.delete.php?id="+id, options)
        .then(response => response.text())
        .then(data => {
            console.log(data);
            read();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function statusRol(id,estado){
    let data = `id=${id}&estado=${estado}`

    let options = {
        method: 'POST',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch("../controllers/productos.estado.php",options)
    .then(response => response.json())
    .then(data => {
        console.log(data);
        read()
    })
}

function actualizarEstado(){
    let input = document.getElementById("tablePro").getElementsByClassName("form-check-input")
    let label = document.getElementById("tablePro").getElementsByClassName("form-check-label")

    for (let i = 0; i < input.length; i++) {
        if(label[i].innerHTML == 'Activo'){
            input[i].setAttribute("checked","");
        }
    }
}

function modal(idPro){
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
};
