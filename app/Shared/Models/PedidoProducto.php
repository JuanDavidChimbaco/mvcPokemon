<?php

namespace Shared\Models;

use Core\Model;

class PedidoProducto extends Model
{
    protected static string $table = 'pedido_productos';
    protected static string $primaryKey = 'id';
    
    protected static array $fillable = [
        'pedido_id', 'producto_id', 'cantidad', 'precio_unitario', 'subtotal'
    ];
    
    protected static array $casts = [
        'id' => 'int',
        'pedido_id' => 'int',
        'producto_id' => 'int',
        'cantidad' => 'int',
        'precio_unitario' => 'float',
        'subtotal' => 'float',
    ];
    
    public function pedido(): ?Pedido
    {
        return Pedido::find($this->pedido_id);
    }
    
    public function producto(): ?Producto
    {
        return Producto::find($this->producto_id);
    }
}