function mostrarDatosP() {
    let productosCarrito = localStorage.Pedido;

    let c = JSON.parse(productosCarrito);
    console.log(c);

    let totalPorProducto = 0;
    let totalFinal = 0;

    c.map(item => {
        const totalProducto = item.producto.precioPro * item.producto.cantidad;
        totalPorProducto += totalProducto;
        totalFinal += totalProducto;
        return
    });

    let tableDatos = `
    ${c.map((item, index) =>
        `
        <tr>
            <th scope="row">${index + 1}</th>
            <td>${item.producto.id}</td>
            <td>${item.producto.nombrePro}</td>
            <td>$${item.producto.precioPro}</td>
            <td>${item.producto.cantidad}</td>
            <td>${item.producto.categoriaP}</td>
            <td>$${item.producto.precioPro * item.producto.cantidad}</td>
        </tr>
    `
    ).join('')
        }
    
    `
    tableDatos += `<tr><td colspan="6">Total</td><td>$${totalFinal}</td></tr>`
    const tablaContainer = document.getElementById('tabla-container');
    tablaContainer.innerHTML = tableDatos;
}

function retorno() {
    Swal.fire({
        title: 'Estas Seguro?',
        text: "Perdera el proceso de la compra si hace esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Al Inicio!',
        cancelButtonText: 'No, quedarme!!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'index.php'
        }
    })
}

function confirmShop() {
    Swal.fire({
        title: 'Estas Seguro?',
        text: "Se hara una Compra!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, A comprar!',
        cancelButtonText: 'No, Mejor espero!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Ooh...Compra Realizada!',
                text: 'Ahora a Pagar :) ',
                html:
                    'Puedes cancelar este link <b><i class="fa fa-solid fa-arrow-right"></i></b> ' +
                    ' <a href="https://www.pse.com.co/persona">PSE</a> ' +
                    ' o a nuestra cuenta Davivienda 055504160000043117',
            }).then((result) => {
                if (result.isConfirmed) {
                    realizarCompra()
                    window.location.href = 'index.php'
                }
            })
        }
    })
}

async function realizarCompra() {
    try {
        const generateCode = () => {
            let counter = localStorage.getItem('counter');
            if (!counter) {
                counter = 1;
            }
            const code = `COD${counter.toString().padStart(3, '0')}`;
            localStorage.setItem('counter', parseInt(counter) + 1);
            return code;
        };
        let codigo = generateCode();
        const response1 = await fetch('http://localhost/mvcPokemon/controllers/comprasProductos.control.php?code=' + codigo);
        const data = await response1.text();
        let ultimoID = JSON.parse(data)
        // -------------------------------------------------------------------------------------------
        let productosCarrito = JSON.parse(localStorage.Pedido)
        let options = {
            method: 'POST',
            body: JSON.stringify({ ultimoID: ultimoID, productosCarrito: productosCarrito }),
            headers: {
                'Content-Type': 'application/json'
            }
        }
        const response2 = await fetch('http://localhost/mvcPokemon/controllers/comprasProductos.control.php', options);
        const data2 = await response2.text();
        console.log(data2);

    } catch (error) {
        console.log(error);
    }
    window.location.href = 'index.php';
}

let datauser = "";

async function datosUserCompra() {
    const response = await fetch('../../controllers/usuarios.data.php')
    const data = await response.json()
    datauser = data;
}
datosUserCompra();
