<?php

namespace Modules\Shop\Services;

use Core\Session;
use Shared\Models\Producto;

class ShoppingCart
{
    private const SESSION_KEY = 'shop_cart';

    public function __construct(private readonly Session $session) {}

    public function items(): array
    {
        $items = [];
        foreach ($this->rawItems() as $id => $saved) {
            if (!is_array($saved) || !isset($saved['producto_id'], $saved['cantidad'])) {
                continue;
            }

            $product = Producto::find($saved['producto_id']);
            if ($product) {
                $quantity = (int)$saved['cantidad'];
                $items[] = [
                    'id' => (string)$id,
                    'producto_id' => (int)$product->id,
                    'nombre' => $product->nombrePro,
                    'precio' => (float)$product->precioPro,
                    'cantidad' => $quantity,
                    'subtotal' => (float)$product->precioPro * $quantity,
                    'imagen' => $product->urlFoto,
                    'categoria' => $product->categoria()?->nombreCat ?? '',
                    'stock' => (int)$product->cantidadPro,
                    'available' => $product->isAvailable($quantity),
                ];
                continue;
            }

            $items[] = [
                'id' => (string)$id,
                'producto_id' => (int)$saved['producto_id'],
                'nombre' => (string)($saved['nombre'] ?? 'Producto no disponible'),
                'precio' => (float)($saved['precio'] ?? 0),
                'cantidad' => (int)$saved['cantidad'],
                'subtotal' => (float)($saved['precio'] ?? 0) * (int)$saved['cantidad'],
                'imagen' => $saved['imagen'] ?? null,
                'categoria' => (string)($saved['categoria'] ?? ''),
                'stock' => 0,
                'available' => false,
            ];
        }

        return $items;
    }

    public function summary(?array $items = null): array
    {
        $items ??= $this->items();
        $subtotal = array_sum(array_column($items, 'subtotal'));
        $tax = round($subtotal * 0.19, 2);

        return [
            'subtotal' => $subtotal,
            'shipping' => 0.0,
            'tax' => $tax,
            'total' => $subtotal + $tax,
            'item_count' => count($items),
            'quantity_count' => array_sum(array_column($items, 'cantidad')),
            'can_checkout' => $items !== [] && !array_filter($items, static fn(array $item): bool => !$item['available']),
        ];
    }

    public function add(int $productId, int $quantity): array
    {
        $product = Producto::find($productId);
        if (!$product || !$product->isAvailable()) {
            return ['success' => false, 'message' => 'El producto no está disponible.'];
        }

        $cart = $this->rawItems();
        $id = (string)$product->id;
        $current = (int)($cart[$id]['cantidad'] ?? 0);
        $requested = $current + $quantity;
        if (!$product->isAvailable($requested)) {
            $remaining = max(0, (int)$product->cantidadPro - $current);
            return [
                'success' => false,
                'message' => $remaining > 0
                    ? "Solo puedes agregar {$remaining} unidad(es) más de este producto."
                    : 'Ya tienes todo el inventario disponible en el carrito.',
            ];
        }

        $cart[$id] = [
            'producto_id' => (int)$product->id,
            'nombre' => $product->nombrePro,
            'precio' => (float)$product->precioPro,
            'cantidad' => $requested,
            'imagen' => $product->urlFoto,
            'categoria' => $product->categoria()?->nombreCat ?? '',
        ];
        $this->save($cart);

        return ['success' => true] + $this->summary();
    }

    public function update(int $productId, int $quantity): array
    {
        $cart = $this->rawItems();
        $id = (string)$productId;
        if (!isset($cart[$id])) {
            return ['success' => false, 'message' => 'El producto ya no está en el carrito.'];
        }

        if ($quantity === 0) {
            unset($cart[$id]);
            $this->save($cart);
            return ['success' => true] + $this->summary();
        }

        $product = Producto::find($productId);
        if (!$product || !$product->isAvailable($quantity)) {
            return ['success' => false, 'message' => 'La cantidad supera el inventario disponible.'];
        }

        $cart[$id] = [
            'producto_id' => (int)$product->id,
            'nombre' => $product->nombrePro,
            'precio' => (float)$product->precioPro,
            'cantidad' => $quantity,
            'imagen' => $product->urlFoto,
            'categoria' => $product->categoria()?->nombreCat ?? '',
        ];
        $this->save($cart);

        $items = $this->items();
        $updatedItem = null;
        foreach ($items as $item) {
            if ($item['producto_id'] === $productId) {
                $updatedItem = $item;
                break;
            }
        }

        return ['success' => true, 'item' => $updatedItem] + $this->summary($items);
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    public function rawItems(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }

    public function save(array $items): void
    {
        $this->session->put(self::SESSION_KEY, $items);
    }
}
