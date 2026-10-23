<?php

namespace Modules\Shop\Controllers\Api;

use Core\ApiController;
use Shared\Models\Categoria;

class CategoryController extends ApiController
{
    public function index(): \Core\Response
    {
        $categorias = Categoria::ordered()->get();
        
        // Agregar datos extra para el frontend
        $categorias = array_map(function ($cat) {
            return [
                'id' => $cat->id,
                'nombre' => $cat->nombreCat,
                'descripcion' => $cat->descripcion ?? '',
                'icono' => $cat->getIconUrl(),
                'color' => $cat->getColor(),
                'gradient' => $cat->getGradient(),
                'productos_count' => $cat->productosCount(),
            ];
        }, $categorias);
        
        return $this->success($categorias);
    }

    public function show(int $id): \Core\Response
    {
        $categoria = Categoria::find($id);
        
        if (!$categoria) {
            return $this->notFound('Categoría no encontrada');
        }
        
        return $this->success([
            'id' => $categoria->id,
            'nombre' => $categoria->nombreCat,
            'descripcion' => $categoria->descripcion ?? '',
            'icono' => $categoria->getIconUrl(),
            'color' => $categoria->getColor(),
            'gradient' => $categoria->getGradient(),
            'productos_count' => $categoria->productosCount(),
        ]);
    }
}