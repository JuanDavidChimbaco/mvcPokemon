"use strict";

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance"); }

function _iterableToArrayLimit(arr, i) { if (!(Symbol.iterator in Object(arr) || Object.prototype.toString.call(arr) === "[object Arguments]")) { return; } var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

function mostrarVistaPrevia() {
  var url = document.getElementById("url").value;
  var vistaPrevia = document.getElementById("vistaPrevia"); // Limpiar la vista previa

  vistaPrevia.innerHTML = ""; // Crear un elemento de imagen

  var imagen = document.createElement("img");
  imagen.src = url;
  imagen.width = 200; // Mostrar la vista previa de la imagen

  vistaPrevia.appendChild(imagen);
}

function mostrarVistaPreviaUp() {
  var url = document.getElementById("urlImagen").value;
  var vistaPreviaU = document.getElementById("vistaPreviaUpdate"); // Limpiar la vista previa

  vistaPreviaU.innerHTML = ""; // Crear un elemento de imagen

  var imagen = document.createElement("img");
  imagen.src = url;
  imagen.style.width = "100%"; // Mostrar la vista previa de la imagen

  vistaPreviaU.appendChild(imagen);
}

function created() {
  url = "../controllers/productos.create.php"; //* Informacion del formulario

  var nombre = document.getElementById("txtNombre").value;
  var precio = document.getElementById("txtPrecio").value;
  var cantidad = document.getElementById("txtCantidad").value;
  var descripcion = document.getElementById("txtDescripcion").value;
  var fotoUrl = document.getElementById("url").value;
  var data2 = "nombrePro=".concat(nombre, "&precioPro=").concat(precio, "&cantidadPro=").concat(cantidad, "&descripPro=").concat(descripcion, "&fotoUrl=").concat(fotoUrl);
  var options2 = {
    method: 'POST',
    body: data2,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
  };
  fetch(url, options2).then(function (response) {
    return response.text();
  }).then(function (data) {
    console.log(data);
    read();
    document.getElementById('txtNombre').value = "";
    document.getElementById("txtPrecio").value = "";
    document.getElementById("txtCantidad").value = "";
    document.getElementById("txtDescripcion").value = "";
    document.getElementById("url").value = "";
    vistaPrevia.innerHTML = "";
  })["catch"](function (error) {
    console.error("Error en la peticion: ".concat(error));
  });
}

var dataTable = null;

function read() {
  var response, data, table, _iteratorNormalCompletion, _didIteratorError, _iteratorError, _iterator, _step, _step$value, index, Element;

  return regeneratorRuntime.async(function read$(_context) {
    while (1) {
      switch (_context.prev = _context.next) {
        case 0:
          _context.prev = 0;
          _context.next = 3;
          return regeneratorRuntime.awrap(fetch("../controllers/productos.read.php"));

        case 3:
          response = _context.sent;
          _context.next = 6;
          return regeneratorRuntime.awrap(response.json());

        case 6:
          data = _context.sent;
          table = "";
          _iteratorNormalCompletion = true;
          _didIteratorError = false;
          _iteratorError = undefined;
          _context.prev = 11;

          for (_iterator = data.entries()[Symbol.iterator](); !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
            _step$value = _slicedToArray(_step.value, 2), index = _step$value[0], Element = _step$value[1];
            table += "<tr>\n                  <th scope='row'>".concat(index + 1, "</th>\n                  <td>").concat(Element.nombrePro, "</td>\n                  <td>").concat(Element.precioPro, "</td>\n                  <td>").concat(Element.cantidadPro, "</td>\n                  <td>").concat(Element.categoria, "</td>\n                  <td><img src=\"").concat(Element.urlFoto, "\" alt=\"").concat(Element.nombrePro, "\" width=\"100\"></td>\n                  <td>\n                      <div class=\"form-check form-switch\">\n                        <input class=\"form-check-input\" type=\"checkbox\" role=\"switch\" id=\"Switch").concat(Element.nombrePro, "\" onclick=\"statusP('").concat(Element.id, "','").concat(Element.estado, "')\" ").concat(Element.estado == "A" ? "checked" : "", ">\n                        <label class=\"form-check-label\" for=\"Switch").concat(Element.nombreRol, "\">").concat(Element.estado == "A" ? "Activo" : "Inactivo", "</label>\n                      </div>\n                  </td>\n                  <td>\n                      <a onclick=\"readID(").concat(Element.id, ")\" data-bs-toggle=\"modal\" data-bs-target=\"#updateModal\" class=\"btn btn-warning\">\n                          <i class=\"fa fa-edit text-dark\"></i>\n                      </a>\n                      <a onclick=\"modal(").concat(Element.id, ")\" class=\"btn btn-danger\">\n                          <i class=\"fa fa-trash text-dark\"></i>\n                      </a>\n                  </td>\n              </tr>");
          }

          _context.next = 19;
          break;

        case 15:
          _context.prev = 15;
          _context.t0 = _context["catch"](11);
          _didIteratorError = true;
          _iteratorError = _context.t0;

        case 19:
          _context.prev = 19;
          _context.prev = 20;

          if (!_iteratorNormalCompletion && _iterator["return"] != null) {
            _iterator["return"]();
          }

        case 22:
          _context.prev = 22;

          if (!_didIteratorError) {
            _context.next = 25;
            break;
          }

          throw _iteratorError;

        case 25:
          return _context.finish(22);

        case 26:
          return _context.finish(19);

        case 27:
          ;

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
            buttons: [{
              extend: 'copy',
              text: '<i class="fa fa-copy"></i>',
              titleAttr: 'Copy',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
              },
              className: 'bg-success'
            }, {
              extend: 'csv',
              text: '<i class="fa fa-file-csv"></i>',
              titleAttr: 'CSV',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
              },
              className: 'bg-warning'
            }, {
              extend: 'excel',
              text: '<i class="fa fa-file-excel"></i>',
              titleAttr: 'Excel',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
              },
              className: 'bg-danger'
            }, {
              extend: 'pdf',
              text: '<i class="fa fa-file-pdf"></i>',
              titleAttr: 'PDF',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
              },
              className: 'bg-primary'
            }, {
              extend: 'print',
              text: '<i class="fa fa-print"></i>',
              titleAttr: 'Print',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
              },
              className: 'bg-secondary'
            }]
          });
          _context.next = 36;
          break;

        case 33:
          _context.prev = 33;
          _context.t1 = _context["catch"](0);
          console.log("Error al consultar", _context.t1);

        case 36:
        case "end":
          return _context.stop();
      }
    }
  }, null, null, [[0, 33], [11, 15, 19, 27], [20,, 22, 26]]);
}

function update() {
  //* Informacion del formulario
  var nombre = document.getElementById("nombreProducto").value;
  var precio = document.getElementById("precioProducto").value;
  var cantidad = document.getElementById("cantidadProducto").value;
  var descripcion = document.getElementById("descripProducto").value;
  var fotoUrl = document.getElementById("urlImagen").value;
  var idP = localStorage.idP; // Obtener el id del producto del LocalStorage 

  var data2 = "id=".concat(idP, "&nombrePro=").concat(nombre, "&precioPro=").concat(precio, "&cantidadPro=").concat(cantidad, "&descripPro=").concat(descripcion, "&fotoUrl=").concat(fotoUrl); // opciones de la peticion con string

  var options = {
    method: 'POST',
    body: data2,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
  };
  fetch("../controllers/productos.update.php", options) // Aqui se puede usasr options 
  .then(function (response) {
    return response.json();
  }).then(function (data) {
    read();
  })["catch"](function (error) {
    console.error('Error:', error);
  });
}

function readID(id) {
  fetch("../controllers/productos.readId.php?id=" + id).then(function (response) {
    return response.json();
  }).then(function (data) {
    var datos = '';
    datos = "\n                <div class=\"container\">\n                <form>\n                    <div class=\"row\">\n                        <div class=\"col\">\n                            <label for=\"nombreRol\">Nombre del Producto:</label>\n                            <input type=\"text\" class=\"form-control\" id=\"nombreProducto\" name=\"nombreProducto\" value=\"".concat(data.nombrePro, "\" required> \n                        </div>\n                        <div class=\"col\">\n                            <label for=\"nombreRol\">Precio del Producto:</label>\n                            <input type=\"number\" class=\"form-control\" id=\"precioProducto\" name=\"precioProducto\" value=\"").concat(data.precioPro, "\" required> \n                        </div>\n                    </div>\n                    <div class=\"row\">\n                        <div class=\"col\">\n                            <label for=\"nombreRol\">Cantidad del Producto:</label>\n                            <input type=\"number\" class=\"form-control\" id=\"cantidadProducto\" name=\"cantidadProducto\" value=\"").concat(data.cantidadPro, "\" required> \n                        </div>\n                        <div class=\"col\">\n                            <label for=\"nombreRol\">Descripcion del Producto:</label>\n                            <input type=\"text\" class=\"form-control\" id=\"descripProducto\" name=\"descripProducto\" value=\"").concat(data.categoria, "\" required> \n                        </div>\n                    </div>\n                    <div class=\"row\">\n                        <div class=\"col\">\n                            <img src=\"").concat(data.urlFoto, "\" alt=\"").concat(data.nombrePro, "\" class=\"imagen\">\n                        </div>\n                        <div class=\"col\">\n                            <div id=\"vistaPreviaUpdate\">\n                            \n                            </div>\n                        </div>\n                    </div>\n                    <div class=\"row\">\n                    <label for=\"urlImagen\">(url) Imagen Nueva:</label>\n                        <input type=\"text\" class=\"form-control\" name=\"urlImagen\" id=\"urlImagen\" onchange=\"mostrarVistaPreviaUp()\" placeholder=\"example\" value=\"").concat(data.urlFoto, "\"required>\n                    </div>\n                </form>\n                </div>\n                "); // guardo el id en el local storage es mas seguro que con input hidden

    localStorage.idP = data.id;
    document.getElementById('contenidoUpdate').innerHTML = datos;
  });
}

function deleteById(id) {
  // Opciones de la petición
  var options = {
    method: 'POST',
    body: JSON.stringify({
      id: id
    }),
    headers: {
      'Content-Type': 'application/json'
    }
  };
  fetch("../controllers/productos.delete.php?id=" + id, options).then(function (response) {
    return response.text();
  }).then(function (data) {
    console.log(data);
    read();
  })["catch"](function (error) {
    console.error('Error:', error);
  });
}

function statusP(id, estado) {
  var data = "id=".concat(id, "&estado=").concat(estado);
  var options = {
    method: 'POST',
    body: data,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
  };
  fetch("../controllers/productos.estado.php", options).then(function (response) {
    return response.json();
  }).then(function (data) {
    console.log(data);
    read();
  });
}

function modal(idPro) {
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
      deleteById(idPro);
      swalWithBootstrapButtons.fire('Eliminado!', 'Su Producto ha sido Eliminado.', 'success');
    } else if (
    /* cerrar el Modal si se cancela */
    result.dismiss === Swal.DismissReason.cancel) {
      swalWithBootstrapButtons.fire('Cancelado', 'Tu Producto esta seguro ... Por el momento :)', 'error');
    }
  });
}

window.onload = function (event) {
  read();
};