let dataTable = null;

async function read() {
  try {
    const response = await fetch("../controllers/compras.read.php");
    const data = await response.json();
    let table = "";
    for (const [index, Element] of data.entries()) {
      table += `<tr>
                  <th scope='row'>${index + 1}</th>
                  <td>${Element.codigoCom}</td>
                  <td>${Element.fechaCom}</td>
                  <td>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="Switch${Element.nombrePro}" onclick="statusP('${Element.id}','${Element.estado}')" ${Element.estado == "A" ? "checked" : ""}>
                        <label class="form-check-label" for="Switch${Element.nombreRol}">${Element.estado == "A" ? "Activo" : "Inactivo"}</label>
                      </div>
                  </td>
                  <td>${Element.nombrePro}</td>
                  <td><img src="${Element.urlFoto}" alt="${Element.nombrePro}" width="100"></td>
                  <td>${Element.precioPro}</td>
                  <td>${Element.cantidadProCom}</td>
                  <td>${Element.cantidadProCom*Element.precioPro}</td>
              </tr>`;
    };
    if (dataTable) {
      dataTable.destroy();
    }
    document.getElementById('tablePro').innerHTML = table;
    dataTable = new DataTable('#tableProducts', {
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
            columns: [0, 1, 2, 3, 4, 5, 6]
          },
          className: 'bg-success'
        },
        {
          extend: 'csv',
          text: '<i class="fa fa-file-csv"></i>',
          titleAttr: 'CSV',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          },
          className: 'bg-warning'
        },
        {
          extend: 'excel',
          text: '<i class="fa fa-file-excel"></i>',
          titleAttr: 'Excel',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          },
          className: 'bg-danger'
        },
        {
          extend: 'pdf',
          text: '<i class="fa fa-file-pdf"></i>',
          titleAttr: 'PDF',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          },
          className: 'bg-primary'
        },
        {
          extend: 'print',
          text: '<i class="fa fa-print"></i>',
          titleAttr: 'Print',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          },
          className: 'bg-secondary'
        }
      ]
    });

  } catch (error) {
    console.log("Error al consultar", error);
  }
}

function statusP(id, estado) {
  let data = `id=${id}&estado=${estado}`

  let options = {
    method: 'POST',
    body: data,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
  }

  fetch("../controllers/compras.read.php", options)
    .then(response => response.json())
    .then(data => {
      console.log(data);
      read()
    })
}

window.onload = (event) => {
  read();
};
