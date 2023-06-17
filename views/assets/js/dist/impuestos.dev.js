"use strict";

function create() {
  var nombreImp, porcentaje, data, options, response, _data;

  return regeneratorRuntime.async(function create$(_context) {
    while (1) {
      switch (_context.prev = _context.next) {
        case 0:
          nombreImp = document.getElementById('txtNombreImp').value;
          porcentaje = document.getElementById('txtPorcentaje').value; // const datos = `nombreImp=${nombreImp}&porcentaje=${porcentaje}`;

          if (!(nombreImp.trim() === "" && porcentaje.trim() === "")) {
            _context.next = 6;
            break;
          }

          alert("Campo vacío");
          _context.next = 22;
          break;

        case 6:
          data = {
            nombre: nombreImp,
            porcentaje: porcentaje,
            action: 'create'
          };
          options = {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
              'Content-Type': 'application/json'
            }
          };
          _context.prev = 8;
          _context.next = 11;
          return regeneratorRuntime.awrap(fetch("../controllers/impuestos.create.php", options));

        case 11:
          response = _context.sent;
          _context.next = 14;
          return regeneratorRuntime.awrap(response.text());

        case 14:
          _data = _context.sent;
          read();
          clean();
          _context.next = 22;
          break;

        case 19:
          _context.prev = 19;
          _context.t0 = _context["catch"](8);
          console.error("Error al crear el Impuesto: ".concat(_context.t0));

        case 22:
        case "end":
          return _context.stop();
      }
    }
  }, null, null, [[8, 19]]);
}

var dataTable = null;

function read() {
  var response, data, datos, table;
  return regeneratorRuntime.async(function read$(_context2) {
    while (1) {
      switch (_context2.prev = _context2.next) {
        case 0:
          _context2.prev = 0;
          _context2.next = 3;
          return regeneratorRuntime.awrap(fetch("../controllers/impuestos.create.php"));

        case 3:
          response = _context2.sent;
          _context2.next = 6;
          return regeneratorRuntime.awrap(response.json());

        case 6:
          data = _context2.sent;
          datos = data[0].result2;
          table = "";
          datos.forEach(function (imp, index) {
            table += "\n            <tr>\n                <th scope='row'>".concat(index + 1, "</th>\n                <td>").concat(imp.nombreImp, "</td>\n                <td>").concat(imp.porcentaje, "%</td>\n                <td>\n                    <div class=\"form-check form-switch\">\n                        <input class=\"form-check-input\" type=\"checkbox\" role=\"switch\" id=\"flexSwitchCheckChecked\" onclick=\"status('").concat(imp.id, "','").concat(imp.estado, "')\" ").concat(imp.estado == "A" ? "checked" : "", ">\n                        <label class=\"form-check-label\" for=\"flexSwitchCheckChecked\">").concat(imp.estado == "A" ? "Activo" : "Inactivo", "</label>\n                    </div>\n                </td>\n                <td>").concat(imp.fechaCreacion, "</td>\n                <td>\n                    <a onclick=\"readID(").concat(imp.id, ")\" data-bs-toggle=\"modal\" data-bs-target=\"#updateModal\" class=\"btn btn-warning\">\n                        <i class=\"fa fa-edit text-dark\"></i>\n                    </a>\n                    <a onclick=\"modal(").concat(imp.id, ")\" class=\"btn btn-danger\">\n                        <i class=\"fa fa-trash text-dark\"></i>\n                    </a>\n                </td>\n            </tr>");
          });

          if (!dataTable) {
            _context2.next = 22;
            break;
          }

          document.getElementById('tableImp').classList.add('transition-effect');
          _context2.next = 14;
          return regeneratorRuntime.awrap(new Promise(function (resolve) {
            return setTimeout(resolve, 300);
          }));

        case 14:
          dataTable.destroy();
          document.getElementById('tableImp').innerHTML = table;
          dataTable = new DataTable('#tableImpuestos', {
            language: {
              url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-MX.json'
            },
            retrieve: true,
            dom: 'Bfrtip',
            buttons: [{
              extend: 'copy',
              text: '<i class="fa fa-copy"></i>',
              titleAttr: 'Copy',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              },
              className: 'bg-success'
            }, {
              extend: 'csv',
              text: '<i class="fa fa-file-csv"></i>',
              titleAttr: 'CSV',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              },
              className: 'bg-warning'
            }, {
              extend: 'excel',
              text: '<i class="fa fa-file-excel"></i>',
              titleAttr: 'Excel',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              },
              className: 'bg-danger'
            }, {
              extend: 'pdf',
              text: '<i class="fa fa-file-pdf"></i>',
              titleAttr: 'PDF',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              },
              className: 'bg-primary'
            }, {
              extend: 'print',
              text: '<i class="fa fa-print"></i>',
              titleAttr: 'Print',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              },
              className: 'bg-secondary'
            }]
          });
          _context2.next = 19;
          return regeneratorRuntime.awrap(new Promise(function (resolve) {
            return setTimeout(resolve, 100);
          }));

        case 19:
          document.getElementById('tableImp').classList.remove('transition-effect');
          _context2.next = 24;
          break;

        case 22:
          document.getElementById('tableImp').innerHTML = table;
          dataTable = new DataTable('#tableImpuestos', {
            language: {
              url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-MX.json'
            },
            retrieve: true,
            dom: 'Bfrtip',
            buttons: [{
              extend: 'copy',
              text: '<i class="fa fa-copy"></i>',
              titleAttr: 'Copy',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              },
              className: 'bg-success'
            }, {
              extend: 'csv',
              text: '<i class="fa fa-file-csv"></i>',
              titleAttr: 'CSV',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              },
              className: 'bg-warning'
            }, {
              extend: 'excel',
              text: '<i class="fa fa-file-excel"></i>',
              titleAttr: 'Excel',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              },
              className: 'bg-danger'
            }, {
              extend: 'pdf',
              text: '<i class="fa fa-file-pdf"></i>',
              titleAttr: 'PDF',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              },
              className: 'bg-primary'
            }, {
              extend: 'print',
              text: '<i class="fa fa-print"></i>',
              titleAttr: 'Print',
              exportOptions: {
                columns: [0, 1, 2, 3, 4]
              },
              className: 'bg-secondary'
            }]
          });

        case 24:
          _context2.next = 29;
          break;

        case 26:
          _context2.prev = 26;
          _context2.t0 = _context2["catch"](0);
          console.log(_context2.t0);

        case 29:
        case "end":
          return _context2.stop();
      }
    }
  }, null, null, [[0, 26]]);
}

function clean() {
  document.getElementById('txtNombreImp').value = "";
  document.getElementById('txtPorcentaje').value = "";
}

function update() {
  var nombreImp, porcentaje, idI, datos, options, response, data;
  return regeneratorRuntime.async(function update$(_context3) {
    while (1) {
      switch (_context3.prev = _context3.next) {
        case 0:
          _context3.prev = 0;
          //* Informacion del formulario
          nombreImp = document.getElementById("txtN").value;
          porcentaje = document.getElementById("txtP").value;
          idI = localStorage.idImp; // Obtener el id del LocalStorage

          datos = {
            idU: idI,
            nombreImp: nombreImp,
            porcentajeImp: porcentaje,
            action: 'update'
          }; //* Opciones de la peticion por medio de json

          options = {
            method: 'POST',
            body: JSON.stringify(datos),
            headers: {
              'Content-Type': 'application/json'
            }
          };
          console.log(datos);
          _context3.next = 9;
          return regeneratorRuntime.awrap(fetch("../controllers/impuestos.create.php", options));

        case 9:
          response = _context3.sent;
          _context3.next = 12;
          return regeneratorRuntime.awrap(response.json());

        case 12:
          data = _context3.sent;
          read();
          _context3.next = 19;
          break;

        case 16:
          _context3.prev = 16;
          _context3.t0 = _context3["catch"](0);
          console.error('Error:', _context3.t0);

        case 19:
        case "end":
          return _context3.stop();
      }
    }
  }, null, null, [[0, 16]]);
}

function readID(idI) {
  fetch("../controllers/impuestos.create.php?id=".concat(idI)).then(function (response) {
    return response.json();
  }).then(function (data) {
    console.log(data);
    var result4 = data[0].result4;
    var datos = '';
    datos = "\n                <div class=\"container\">\n                    <div class=\"row justify-content-center\">\n                        <div class=\"col-6\">\n                            <form>\n                                <div class=\"form-group\">\n                                    <label for=\"nombreRol\">Nombre del Impuesto: </label>\n                                    <input type=\"text\" class=\"form-control\" id=\"txtN\" name=\"nombreImp\" value=\"".concat(result4.nombreImp, "\" required> \n                                </div>\n                                <div class=\"form-group\">\n                                    <label for=\"nombreRol\">Porcentaje: </label>\n                                    <input type=\"number\" class=\"form-control\" id=\"txtP\" name=\"porcentaje\" value=\"").concat(result4.porcentaje, "\" required> \n                                </div>\n                             </form>\n                        </div>\n                    </div>\n                </div>\n                ");
    localStorage.idImp = result4.id;
    document.getElementById('contenidoUpdate').innerHTML = datos;
  });
}

function deleteById(id) {
  // Opciones de la petición
  var options = {
    method: 'POST',
    body: JSON.stringify({
      idD: id,
      action: 'delete'
    }),
    headers: {
      'Content-Type': 'application/json'
    }
  };
  fetch("../controllers/impuestos.create.php?id=".concat(id), options).then(function (response) {
    return response.text();
  }).then(function (data) {
    read();
  })["catch"](function (error) {
    console.error('Error:', error);
  });
}

function status(id, estado) {
  var data2, options, response, data;
  return regeneratorRuntime.async(function status$(_context4) {
    while (1) {
      switch (_context4.prev = _context4.next) {
        case 0:
          data2 = {
            idE: id,
            estadoE: estado,
            action: 'estado'
          };
          options = {
            method: 'POST',
            body: JSON.stringify(data2),
            headers: {
              'Content-Type': 'application/json'
            }
          };
          _context4.prev = 2;
          _context4.next = 5;
          return regeneratorRuntime.awrap(fetch("../controllers/impuestos.create.php", options));

        case 5:
          response = _context4.sent;
          _context4.next = 8;
          return regeneratorRuntime.awrap(response.text());

        case 8:
          data = _context4.sent;
          read();
          _context4.next = 15;
          break;

        case 12:
          _context4.prev = 12;
          _context4.t0 = _context4["catch"](2);
          console.error(_context4.t0);

        case 15:
        case "end":
          return _context4.stop();
      }
    }
  }, null, null, [[2, 12]]);
}

function modal(idImp) {
  var swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
  });
  swalWithBootstrapButtons.fire({
    title: 'Estas Seguro?',
    text: "No podras Revertir Esto!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'SI, Eliminar!',
    cancelButtonText: 'No, Cancelar!',
    reverseButtons: true
  }).then(function (result) {
    if (result.isConfirmed) {
      deleteById(idImp);
      swalWithBootstrapButtons.fire('Eliminado!', 'Su impuesto ha sido Eliminado.', 'success');
    } else if (
    /* cerrar el Modal si se cancela */
    result.dismiss === Swal.DismissReason.cancel) {
      swalWithBootstrapButtons.fire('Cancelado', 'error');
    }
  });
}

window.onload = function (event) {
  read();
};