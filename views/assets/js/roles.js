function created() {
    url = "../controllers/roles.create.php"

    //* Informacion del formulario
    var data = `nameRol=${document.getElementById("nameRol").value}`
    console.log(data);
    if (data == "nameRol=") {
        alert("Campo vacio")
    }else{
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
            location.reload()
            .catch(error => {
                console.error(`Error al crear el rol: ${error}`);
            })
    }

}

function read() {
    fetch("../controllers/roles.read.php")
        .then(response => response.json())
        .then(data => {
            console.log(data);
            let table = ""
            data.forEach((Element, index) => {
                table += `<tr>
                        <th scope='row'>${index + 1}</th>
                        <td>${Element.nombreRol}</td>
                        <td>
                            <div class="form-check form-switch">
                            <input onclick="statusRol('${Element.id}','${Element.estado}')" class="form-check-input" type="checkbox" role="switch" id="Switch${Element.nombreRol}">
                            <!--"${Element.estado == "A" ? "checked" : ""}-->
                            <label class="form-check-label" for="Switch${Element.nombreRol}">${Element.estado == "A" ? "Activo" : "Inactivo"}</label>
                            </div>
                        </td>
                        <td>${Element.fechaCreacion}</td>
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
            document.getElementById('tableRol').innerHTML = table;
            let tables = new DataTable('#tableRoles',{
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
                            columns: [0, 1, 2, 3]
                          },
                          className: 'bg-success'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv"></i>',
                        titleAttr: 'CSV',
                        exportOptions: {
                          columns: [0, 1, 2, 3]
                        },
                        className: 'bg-warning'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i>',
                        titleAttr: 'Excel',
                        exportOptions: {
                          columns: [0, 1, 2, 3]
                        },
                        className: 'bg-danger'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i>',
                        titleAttr: 'PDF',
                        exportOptions: {
                          columns: [0, 1, 2, 3]
                        },
                        className: 'bg-primary'
                    },
                    {
                        extend:'print',
                        text: '<i class="fa fa-print"></i>',
                        titleAttr: 'Print',
                        exportOptions:{
                            columns: [0, 1, 2, 3]
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
    var nombreRol = document.getElementById("nombreRol").value
    var id = document.getElementById("idRol").value // Obtener el id del input hidden 

    let idR = localStorage.id // Obtener el id del LocalStorage 

    var data = {
        rol: nombreRol,
        id: id 
    };

    // se puede dejar el id del campo input hidden o usar el idRol del localStorage(Mas Seguro).

    let data2 = `nombreRol=${nombreRol}&id=${idR}`;

    //* Opciones de la peticion por medio de json 
    var options = {
        method: 'POST',
        body: JSON.stringify(data),
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

    fetch("../controllers/roles.update.php",options2) // Aqui se puede usasr options o options2
    .then(response => response.json())
    .then(data => {
        console.log(data);
        read();
    })
    .catch(error=>{
        console.error('Error:',error)
    });
}

function readID(id) {
    fetch("../controllers/roles.readId.php?id="+id)
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
                                    <input type="text" class="form-control" id="nombreRol" name="nombreRol" value="${data.nombreRol}" required> 
                                </div>
                                    <input type="hidden" class="form-control" id="idRol" name="idRol" value="${data.id}">
                             </form>
                        </div>
                    </div>
                </div>
                `
            localStorage.id = data.id;
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

function statusRol(id,estado){
    let data = `id=${id}&estado=${estado}`

    let options = {
        method: 'POST',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    fetch("../controllers/roles.estado.php",options)
    .then(response => response.json())
    .then(data => {
        console.log(data);
        read()
    })
}

function actualizarEstado(){
    let input = document.getElementById("tableRol").getElementsByClassName("form-check-input")
    let label = document.getElementById("tableRol").getElementsByClassName("form-check-label")

    for (let i = 0; i < input.length; i++) {
        if(label[i].innerHTML == 'Activo'){
            input[i].setAttribute("checked","");
        }
    }
}

function modal(idrol){
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
            deleteById(idrol)
          swalWithBootstrapButtons.fire(
            'Eliminado!',
            'Su Rol ha sido Eliminado.',
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
            'Tu Rol esta seguro ... Por el momento :)',
            'error'
          )
        }
      })
}

window.onload = (event) => {
    read();
};
