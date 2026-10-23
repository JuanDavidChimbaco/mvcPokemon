<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Shared\Models\Producto;
use Shared\Models\Categoria;
use Shared\Models\Usuario;
use Shared\Models\Rol;

class AdminController extends Controller
{
    public function dashboard(): \Core\Response
    {
        $stats = [
            'productos' => Producto::query()->count(),
            'productos_activos' => Producto::query()->where('estado', '=', 'A')->count(),
            'categorias' => Categoria::query()->count(),
            'usuarios' => Usuario::query()->count(),
            'roles' => Rol::query()->count(),
            'stock_bajo' => Producto::lowStock(5)->count(),
        ];
        
        $productosRecientes = Producto::query()
            ->leftJoin('categorias', 'productos.categoria', '=', 'categorias.id')
            ->select(['productos.*', 'categorias.nombreCat as categoria_nombre'])
            ->orderBy('productos.id', 'desc')
            ->limit(5)
            ->get(Producto::class);
        $lowStock = Producto::lowStock(10)->limit(5)->get(Producto::class);
        
        return $this->view('admin/dashboard/index', [
            'stats' => $stats,
            'productosRecientes' => $productosRecientes,
            'lowStock' => $lowStock,
        ]);
    }
}