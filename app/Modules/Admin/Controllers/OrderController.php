<?php

namespace Modules\Admin\Controllers;

use Core\Controller;
use Core\Response;
use Shared\Models\Pedido;
use Shared\Models\PedidoProducto;

class OrderController extends Controller
{
    public function index(): Response
    {
        $pedidos = Pedido::query()
            ->leftJoin('usuarios', 'pedidos.usuario_id', '=', 'usuarios.id')
            ->select([
                'pedidos.*',
                'usuarios.nombre as cliente_nombre',
                'usuarios.apellido as cliente_apellido',
                'usuarios.correo as cliente_correo',
            ])
            ->orderBy('pedidos.id', 'desc')
            ->get(Pedido::class);

        return $this->view('admin/orders/index', compact('pedidos'));
    }

    public function show(int $id): Response
    {
        $pedido = Pedido::findOrFail($id);
        $usuario = $pedido->usuario();
        $detalles = $pedido->detalles()
            ->leftJoin('productos', 'pedido_productos.producto_id', '=', 'productos.id')
            ->select([
                'pedido_productos.*',
                'productos.nombrePro as producto_nombre',
            ])
            ->get(PedidoProducto::class);

        return $this->view('admin/orders/show', compact('pedido', 'usuario', 'detalles'));
    }

    public function update(int $id): Response
    {
        $data = $this->validate([
            'estado' => 'required|in:pendiente,confirmado,enviado,entregado,cancelado',
        ]);

        try {
            app()->database()->transaction(function () use ($id, $data): void {
                $order = app()->database()->selectOne(
                    'SELECT id, estado FROM pedidos WHERE id = ? FOR UPDATE',
                    [$id]
                );
                if (!$order) {
                    throw new \RuntimeException('El pedido ya no existe.');
                }

                $oldStatus = $order['estado'];
                $newStatus = $data['estado'];
                if ($oldStatus === $newStatus) {
                    return;
                }
                if (
                    $newStatus === Pedido::ESTADO_CANCELADO
                    && in_array($oldStatus, [Pedido::ESTADO_ENVIADO, Pedido::ESTADO_ENTREGADO], true)
                ) {
                    throw new OrderStatusException('No se puede cancelar un pedido enviado o entregado.');
                }

                if ($newStatus === Pedido::ESTADO_CANCELADO || $oldStatus === Pedido::ESTADO_CANCELADO) {
                    $items = app()->database()->select(
                        'SELECT producto_id, cantidad FROM pedido_productos WHERE pedido_id = ? FOR UPDATE',
                        [$id]
                    );

                    foreach ($items as $item) {
                        $productId = (int)$item['producto_id'];
                        $quantity = (int)$item['cantidad'];
                        $product = app()->database()->selectOne(
                            'SELECT id, cantidadPro FROM productos WHERE id = ? FOR UPDATE',
                            [$productId]
                        );
                        if (!$product) {
                            throw new OrderStatusException('No se pudo actualizar el inventario: falta un producto del pedido.');
                        }

                        if ($newStatus === Pedido::ESTADO_CANCELADO) {
                            app()->database()->execute(
                                'UPDATE productos SET cantidadPro = cantidadPro + ?, updated_at = NOW() WHERE id = ?',
                                [$quantity, $productId]
                            );
                            continue;
                        }

                        if ((int)$product['cantidadPro'] < $quantity) {
                            throw new OrderStatusException('No hay suficiente inventario para reactivar este pedido.');
                        }
                        app()->database()->execute(
                            'UPDATE productos SET cantidadPro = cantidadPro - ?, updated_at = NOW() WHERE id = ? AND cantidadPro >= ?',
                            [$quantity, $productId, $quantity]
                        );
                    }
                }

                app()->database()->execute(
                    'UPDATE pedidos SET estado = ?, updated_at = NOW() WHERE id = ?',
                    [$newStatus, $id]
                );
            });
        } catch (OrderStatusException $exception) {
            $this->flash('error', $exception->getMessage());
            return $this->redirect(route('admin.orders.show', $id));
        }

        $this->flash('success', 'El estado del pedido se actualizó correctamente.');
        return $this->redirect(route('admin.orders.show', $id));
    }
}

final class OrderStatusException extends \RuntimeException
{
}
