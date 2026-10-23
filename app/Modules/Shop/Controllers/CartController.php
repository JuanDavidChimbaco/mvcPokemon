<?php

namespace Modules\Shop\Controllers;

use Core\Controller;
use Core\Response;
use Modules\Shop\Services\ShoppingCart;

class CartController extends Controller
{
    public function index(): Response
    {
        $cart = new ShoppingCart($this->session);
        $items = $cart->items();

        return $this->view('shop/cart', [
            'items' => $items,
            'summary' => $cart->summary($items),
            'canCheckout' => $items !== [] && !array_filter($items, static fn(array $item): bool => !$item['available']),
        ]);
    }

    public function add(): Response
    {
        $data = $this->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'integer|min:1',
        ]);
        $result = (new ShoppingCart($this->session))->add(
            (int)$data['producto_id'],
            (int)($data['cantidad'] ?? 1)
        );

        return $this->json($result, $result['success'] ? 200 : 422);
    }

    public function update(): Response
    {
        $data = $this->validate([
            'producto_id' => 'required|integer',
            'cantidad' => 'required|integer|min:0',
        ]);
        $result = (new ShoppingCart($this->session))->update(
            (int)$data['producto_id'],
            (int)$data['cantidad']
        );

        return $this->json($result, $result['success'] ? 200 : 422);
    }

    public function remove(): Response
    {
        $data = $this->validate(['producto_id' => 'required|integer']);
        $result = (new ShoppingCart($this->session))->update((int)$data['producto_id'], 0);

        return $this->json($result, $result['success'] ? 200 : 404);
    }

    public function clear(): Response
    {
        (new ShoppingCart($this->session))->clear();
        return $this->json(['success' => true] + (new ShoppingCart($this->session))->summary());
    }
}
