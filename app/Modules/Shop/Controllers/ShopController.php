<?php

namespace Modules\Shop\Controllers;

use Core\Controller;
use Shared\Models\Producto;
use Shared\Models\Categoria;

class ShopController extends Controller
{
    public function index(): \Core\Response
    {
        // Productos destacados (últimos 8)
        $destacados = array_map([Producto::class, 'hydrate'], Producto::active()
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get());

        // Todas las categorías activas
        $categorias = array_map([Categoria::class, 'hydrate'], Categoria::ordered()->get());

        // Productos para grid principal (paginados)
        $page = (int)$this->request->query('page', 1);
        $productos = Producto::active()
            ->orderBy('id', 'desc')
            ->paginate(12, $page);
        $productos['data'] = array_map([Producto::class, 'hydrate'], $productos['data']);

        return $this->view('shop/index', [
            'destacados' => $destacados,
            'categorias' => $categorias,
            'productos' => $productos,
        ]);
    }

    public function categories(): \Core\Response
    {
        $categorias = Categoria::orderedGet();

        return $this->view('shop/categories', [
            'categorias' => $categorias,
        ]);
    }

    public function category(int $id): \Core\Response
    {
        $categoria = Categoria::findOrFail($id);
        
        $page = (int)$this->request->query('page', 1);
        $sort = $this->request->query('sort', 'newest');
        $priceMin = $this->request->query('price_min');
        $priceMax = $this->request->query('price_max');
        $inStock = $this->request->query('in_stock');

        $query = Producto::byCategory($id);

        // Filtros
        if ($priceMin !== null && $priceMin !== '') {
            $query->where('precioPro', '>=', (float)$priceMin);
        }
        if ($priceMax !== null && $priceMax !== '') {
            $query->where('precioPro', '<=', (float)$priceMax);
        }
        if ($inStock) {
            $query->where('cantidadPro', '>', 0);
        }

        // Ordenamiento
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('precioPro', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('precioPro', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('nombrePro', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nombrePro', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }

        $productos = $query->paginate(12, $page);
        $productos['data'] = array_map([Producto::class, 'hydrate'], $productos['data']);

        return $this->view('shop/category', [
            'categoria' => $categoria,
            'productos' => $productos,
            'sort' => $sort,
            'priceMin' => $priceMin,
            'priceMax' => $priceMax,
            'inStock' => $inStock,
        ]);
    }

    public function product(int $id): \Core\Response
    {
        $producto = Producto::active()->where('productos.id', '=', $id)->first();
        
        if (!$producto) {
            return $this->view('errors.404', [], 404);
        }
        $producto = Producto::hydrate($producto);

        // Productos relacionados (misma categoría, excluyendo el actual)
        $relacionados = array_map([Producto::class, 'hydrate'], Producto::byCategory((int)$producto->categoria)
            ->where('productos.id', '!=', $id)
            ->limit(4)
            ->get());

        return $this->view('shop/product', [
            'producto' => $producto,
            'relacionados' => $relacionados,
            'isFavorite' => in_array(
                (string)$producto->id,
                $this->session->get('shop_wishlist', []),
                true
            ),
        ]);
    }

    public function search(): \Core\Response
    {
        $term = $this->request->query('q', '');
        $page = (int)$this->request->query('page', 1);

        $productos = [
            'data' => [],
            'current_page' => $page,
            'per_page' => 12,
            'total' => 0,
            'last_page' => 1,
        ];
        if ($term) {
            $productos = Producto::search($term)
                ->orderBy('id', 'desc')
                ->paginate(12, $page);
            $productos['data'] = array_map([Producto::class, 'hydrate'], $productos['data']);
        }

        return $this->view('shop/search', [
            'productos' => $productos,
            'term' => $term,
        ]);
    }
}