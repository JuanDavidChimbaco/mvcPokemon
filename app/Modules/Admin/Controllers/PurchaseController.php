<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Core\Response;
use Shared\Models\Compra;
use Shared\Models\CompraProducto;

class PurchaseController extends Controller
{
    public function index(): Response
    {
        $compras = Compra::query()
            ->leftJoin('usuarios', 'compras.usuario_id', '=', 'usuarios.id')
            ->select([
                'compras.*',
                'usuarios.nombre as usuario_nombre',
                'usuarios.apellido as usuario_apellido',
                'usuarios.correo as usuario_correo',
            ])
            ->orderBy('compras.id', 'desc')
            ->get(Compra::class);

        return $this->view('admin/purchases/index', compact('compras'));
    }

    public function show(int $id): Response
    {
        $compra = Compra::findOrFail($id);
        $usuario = $compra->usuario();
        $detalles = $compra->detalles()
            ->leftJoin('productos', 'compra_productos.producto_id', '=', 'productos.id')
            ->select([
                'compra_productos.*',
                'productos.nombrePro as producto_nombre',
            ])
            ->get(CompraProducto::class);

        return $this->view('admin/purchases/show', compact('compra', 'usuario', 'detalles'));
    }
}
