<?php

namespace Modules\Shop\Controllers;

use Core\Controller;
use Core\Response;
use Shared\Models\Producto;

class WishlistController extends Controller
{
    private const SESSION_KEY = 'shop_wishlist';

    public function index(): Response
    {
        $favorites = $this->session->get(self::SESSION_KEY, []);
        $products = [];
        foreach ($favorites as $id) {
            $product = Producto::find((int)$id);
            if ($product && $product->estado === Producto::ESTADO_ACTIVO) {
                $products[] = $product;
            }
        }

        return $this->view('shop/wishlist', ['productos' => $products]);
    }

    public function toggle(): Response
    {
        $data = $this->validate(['producto_id' => 'required|integer']);
        $product = Producto::find($data['producto_id']);
        if (!$product || $product->estado !== Producto::ESTADO_ACTIVO) {
            return $this->json(['success' => false, 'message' => 'El producto no está disponible.'], 404);
        }

        $favorites = $this->session->get(self::SESSION_KEY, []);
        $id = (string)$product->id;
        $isFavorite = !in_array($id, $favorites, true);
        if ($isFavorite) {
            $favorites[] = $id;
        } else {
            $favorites = array_values(array_filter($favorites, static fn(string $favorite): bool => $favorite !== $id));
        }
        $this->session->put(self::SESSION_KEY, $favorites);

        return $this->json([
            'success' => true,
            'is_favorite' => $isFavorite,
            'message' => $isFavorite ? 'Agregado a favoritos.' : 'Eliminado de favoritos.',
        ]);
    }
}
