<?php

namespace Modules\Shop\Controllers\Api;

use Core\ApiController;
use Modules\Shop\Services\ShoppingCart;

class CartController extends ApiController
{
    private const CART_SESSION_KEY = 'shop_cart';
    
    public function index(): \Core\Response
    {
        $cart = new ShoppingCart($this->session);
        $items = $cart->items();

        return $this->success([
            'items' => $items,
            ...$cart->summary($items),
        ]);
    }

    public function add(): \Core\Response
    {
        $validated = $this->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'integer|min:1',
        ]);
        $result = (new ShoppingCart($this->session))->add(
            (int)$validated['producto_id'],
            (int)($validated['cantidad'] ?? 1)
        );

        return $result['success']
            ? $this->success($result, 'Producto agregado al carrito', 201)
            : $this->error($result['message'], 422);
    }

    public function update(): \Core\Response
    {
        $validated = $this->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'required|integer|min:0',
        ]);
        $result = (new ShoppingCart($this->session))->update(
            (int)$validated['producto_id'],
            (int)$validated['cantidad']
        );

        return $result['success']
            ? $this->success($result, 'Carrito actualizado')
            : $this->error($result['message'], 422);
    }

    public function remove(): \Core\Response
    {
        $validated = $this->validate(['producto_id' => 'required|integer']);
        $result = (new ShoppingCart($this->session))->update((int)$validated['producto_id'], 0);

        return $result['success']
            ? $this->success($result, 'Producto eliminado del carrito')
            : $this->error($result['message'], 404);
    }

    public function clear(): \Core\Response
    {
        $cart = new ShoppingCart($this->session);
        $cart->clear();
        return $this->success($cart->summary(), 'Carrito limpiado');
    }

    public function sync(): \Core\Response
    {
        $validated = $this->validate([
            'items' => 'required|array',
        ]);

        $cartService = new ShoppingCart($this->session);
        $cart = [];
        foreach ($validated['items'] as $item) {
            if (!is_array($item) || !filter_var($item['producto_id'] ?? null, FILTER_VALIDATE_INT)) {
                continue;
            }
            $quantity = filter_var($item['cantidad'] ?? null, FILTER_VALIDATE_INT);
            if ($quantity === false || $quantity < 1) {
                continue;
            }
            $added = $cartService->add((int)$item['producto_id'], $quantity);
            if (!$added['success']) {
                return $this->error($added['message'], 422);
            }
        }

        return $this->success($cartService->summary(), 'Carrito sincronizado');
    }
}