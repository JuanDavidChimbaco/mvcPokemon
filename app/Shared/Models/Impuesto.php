<?php

namespace Shared\Models;

use Core\Model;

class Impuesto extends Model
{
    protected static string $table = 'impuestos';
    protected static string $primaryKey = 'id';
    protected static array $fillable = ['nombre', 'porcentaje', 'estado'];
    protected static array $casts = [
        'id' => 'int',
        'porcentaje' => 'float',
        'estado' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
