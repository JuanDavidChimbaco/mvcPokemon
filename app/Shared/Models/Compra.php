<?php

namespace Shared\Models;

use Core\Model;

class Compra extends Model
{
    protected static string $table = 'compras';
    protected static string $primaryKey = 'id';

    protected static array $fillable = ['usuario_id', 'total', 'estado', 'notas'];

    protected static array $casts = [
        'id' => 'int',
        'usuario_id' => 'int',
        'total' => 'float',
        'estado' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuario(): ?Usuario
    {
        return Usuario::find($this->usuario_id);
    }

    public function detalles(): \Core\QueryBuilder
    {
        return CompraProducto::query()->where('compra_id', '=', $this->id);
    }
}
