<?php

namespace Modules\Shop\Controllers;

use Core\Controller;
use Core\Response;
use Modules\Shop\Services\ShoppingCart;
use Modules\Shop\Services\StockUnavailableException;
use Shared\Models\Pedido;
use Shared\Models\PedidoProducto;
use Shared\Models\Producto;

class CheckoutController extends Controller
{
    public function index(): Response
    {
        if ($this->guest()) {
            $this->flash('error', 'Debes iniciar sesión para finalizar la compra.');
            return $this->redirect(route('auth.login') . '?redirect=' . urlencode(route('shop.checkout')));
        }

        $cart = new ShoppingCart($this->session);
        $items = $cart->items();
        if ($items === []) {
            $this->flash('error', 'Tu carrito está vacío.');
            return $this->redirect(route('shop.cart'));
        }

        foreach ($items as $item) {
            if (!$item['available']) {
                $this->flash('error', "{$item['nombre']} ya no tiene suficiente inventario. Ajusta tu carrito.");
                return $this->redirect(route('shop.cart'));
            }
        }

        return $this->view('shop/checkout', [
            'items' => $items,
            'summary' => $cart->summary($items),
            'user' => $this->auth(),
        ]);
    }

    public function process(): Response
    {
        if ($this->guest()) {
            $this->flash('error', 'Debes iniciar sesión para finalizar la compra.');
            return $this->redirect(route('auth.login'));
        }

        $user = $this->auth();
        $validated = $this->validate([
            'nombre' => 'required|string|min:2|max:50',
            'apellido' => 'required|string|min:2|max:50',
            'correo' => 'required|email|max:100|unique:usuarios,correo,' . $user->id . ',id',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|min:3|max:255',
            'ciudad' => 'required|string|min:2|max:100',
            'estado' => 'required|string|min:2|max:100',
            'codigo_postal' => 'required|string|max:10',
            'pais' => 'required|in:CO,MX,AR,CL,PE,OTRO',
            'payment_method' => 'required|in:tarjeta,pse,contraentrega',
            'terms' => 'required|in:1',
        ]);

        $cart = new ShoppingCart($this->session);
        $rawItems = $cart->rawItems();
        if ($rawItems === []) {
            $this->flash('error', 'Tu carrito está vacío.');
            return $this->redirect(route('shop.cart'));
        }

        try {
            $pedido = app()->database()->transaction(function () use ($rawItems, $validated, $user): Pedido {
                $lockedItems = [];
                foreach ($rawItems as $savedItem) {
                    $productId = (int)($savedItem['producto_id'] ?? 0);
                    $quantity = (int)($savedItem['cantidad'] ?? 0);
                    $productData = app()->database()->selectOne(
                        'SELECT id, precioPro, cantidadPro, estado FROM productos WHERE id = ? FOR UPDATE',
                        [$productId]
                    );

                    if (
                        !$productData
                        || $productData['estado'] !== Producto::ESTADO_ACTIVO
                        || $quantity < 1
                        || (int)$productData['cantidadPro'] < $quantity
                    ) {
                        throw new StockUnavailableException(
                            (string)($savedItem['nombre'] ?? 'Un producto') . ' ya no tiene suficiente inventario.'
                        );
                    }

                    $lockedItems[] = [
                        'producto_id' => $productId,
                        'cantidad' => $quantity,
                        'precio' => (float)$productData['precioPro'],
                        'subtotal' => (float)$productData['precioPro'] * $quantity,
                    ];
                }

                $subtotal = array_sum(array_column($lockedItems, 'subtotal'));
                $tax = round($subtotal * 0.19, 2);
                $order = Pedido::create([
                    'usuario_id' => $user->id,
                    'total' => $subtotal + $tax,
                    'estado' => Pedido::ESTADO_PENDIENTE,
                    'direccion_envio' => "{$validated['direccion']}, {$validated['ciudad']}, {$validated['estado']} {$validated['codigo_postal']}, {$validated['pais']}",
                    'notas' => "Método de pago seleccionado: {$validated['payment_method']} (demostración; no se procesó ningún pago).",
                ]);

                foreach ($lockedItems as $item) {
                    PedidoProducto::create([
                        'pedido_id' => $order->id,
                        'producto_id' => $item['producto_id'],
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    app()->database()->execute(
                        'UPDATE productos SET cantidadPro = cantidadPro - ?, updated_at = NOW() WHERE id = ? AND cantidadPro >= ?',
                        [$item['cantidad'], $item['producto_id'], $item['cantidad']]
                    );
                }

                $user->fill([
                    'nombre' => $validated['nombre'],
                    'apellido' => $validated['apellido'],
                    'correo' => $validated['correo'],
                    'telefono' => $validated['telefono'],
                    'direccion' => $validated['direccion'],
                ])->save();

                return $order;
            });
        } catch (StockUnavailableException $exception) {
            $this->flash('error', $exception->getMessage());
            return $this->redirect(route('shop.cart'));
        }

        $cart->clear();
        return $this->redirect(route('shop.checkout.success', ['id' => $pedido->id]));
    }

    public function success(int $id): Response
    {
        $pedido = Pedido::findOrFail($id);
        if (!$this->check() || (int)$pedido->usuario_id !== (int)$this->auth()->id) {
            return $this->view('errors.404', [], 404);
        }

        $detalles = $pedido->detalles()
            ->leftJoin('productos', 'pedido_productos.producto_id', '=', 'productos.id')
            ->select([
                'pedido_productos.*',
                'productos.nombrePro as producto_nombre',
            ])
            ->get();
        $subtotal = array_sum(array_map(static fn($detalle): float => (float)$detalle->subtotal, $detalles));

        return $this->view('shop/checkout/success', [
            'pedido' => $pedido,
            'usuario' => $pedido->usuario(),
            'detalles' => $detalles,
            'subtotal' => $subtotal,
            'tax' => (float)$pedido->total - $subtotal,
        ]);
    }
}
