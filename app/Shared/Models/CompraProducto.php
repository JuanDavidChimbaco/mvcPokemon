<?php

namespace Shared\Models;

use Core\Model;

class CompraProducto extends Model
{
    protected static string $table = 'compra_productos';
    protected static string $primaryKey = 'id';

    protected static array $fillable = [
        'compra_id', 'producto_id', 'cantidad', 'precio_unitario', 'subtotal',
    ];

    protected static array $casts = [
        'id' => 'int',
        'compra_id' => 'int',
        'producto_id' => 'int',
        'cantidad' => 'int',
        'precio_unitario' => 'float',
        'subtotal' => 'float',
    ];
}
