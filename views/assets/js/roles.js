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
    console.log('Hola');
    fetch("../controllers/roles.read.php")
    .then(response => response.json())
    .then(data=>{
        console.log(data)
        let table = ""
        data.forEach((Element,index)=>{
            table +=`<tr>
                        <th scope='row'>${index+1}</th>
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

}

function deletes() {

}
function readID(id){
    console.log(id);
}

window.onload = (event) =>{
    read();
};
