<?php

namespace Modules\Shop\Controllers\Api;

use Core\ApiController;
use Shared\Models\Producto;
use Shared\Models\Categoria;

class ProductController extends ApiController
{
    public function index(): \Core\Response
    {
        $page = (int)$this->request->query('page', 1);
        $perPage = min((int)$this->request->query('per_page', 12), 50);
        $category = $this->request->query('categoria');
        $search = $this->request->query('search');
        $featured = $this->request->boolean('featured');
        
        $query = Producto::active()->orderBy('id', 'desc');
        
        if ($category) {
            $query->where('categoria', '=', $category);
        }
        
        if ($search) {
            $query->where('nombrePro', 'LIKE', "%{$search}%");
        }
        
        if ($featured) {
            $query->limit(8);
            $productos = $query->get();
            return $this->success($productos);
        }
        
        $paginated = $query->paginate($perPage, $page);
        
        return $this->success($paginated['data'], '', 200)
            ->setHeader('X-Pagination', json_encode([
                'current_page' => $paginated['current_page'],
                'per_page' => $paginated['per_page'],
                'total' => $paginated['total'],
                'last_page' => $paginated['last_page'],
            ]));
    }

    public function show(int $id): \Core\Response
    {
        $producto = Producto::active()->where('productos.id', '=', $id)->first();
        
        if (!$producto) {
            return $this->notFound('Producto no encontrado');
        }
        $producto = Producto::hydrate($producto);
        
        // Incluir categoría
        $categoria = $producto->categoria();
        if ($categoria) {
            $producto->categoria_nombre = $categoria->nombreCat;
            $producto->categoria_icono = $categoria->getIconUrl();
            $producto->categoria_color = $categoria->getColor();
        }
        
        return $this->success($producto->toArray());
    }

    public function byCategory(int $id): \Core\Response
    {
        $categoria = Categoria::find($id);
        
        if (!$categoria) {
            return $this->notFound('Categoría no encontrada');
        }
        
        $productos = Producto::byCategory($id)
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();
        
        return $this->success([
            'categoria' => $categoria,
            'productos' => $productos,
        ]);
    }

    public function search(string $term): \Core\Response
    {
        if (strlen($term) < 2) {
            return $this->success([]);
        }
        
        $productos = Producto::search($term)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        $results = array_map(static function (array $producto): array {
            return [
                'id' => $producto['id'],
                'nombrePro' => $producto['nombrePro'],
                'categoria_nombre' => $producto['categoria_nombre'] ?? null,
                'precioPro' => $producto['precioPro'],
                'image_url' => product_image_url($producto['urlFoto'] ?? null),
            ];
        }, $productos);

        return $this->success($results);
    }

    public function featured(): \Core\Response
    {
        $productos = Producto::active()
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();
        
        return $this->success($productos);
    }
}