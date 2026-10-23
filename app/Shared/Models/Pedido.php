<?php

namespace Shared\Models;

use Core\Model;

class Pedido extends Model
{
    protected static string $table = 'pedidos';
    protected static string $primaryKey = 'id';
    
    protected static array $fillable = [
        'usuario_id', 'total', 'estado', 'direccion_envio', 'notas'
    ];
    
    protected static array $casts = [
        'id' => 'int',
        'usuario_id' => 'int',
        'total' => 'float',
        'estado' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_CONFIRMADO = 'confirmado';
    public const ESTADO_ENVIADO = 'enviado';
    public const ESTADO_ENTREGADO = 'entregado';
    public const ESTADO_CANCELADO = 'cancelado';
    
    public function usuario(): ?Usuario
    {
        return Usuario::find($this->usuario_id);
    }
    
    public function detalles(): \Core\QueryBuilder
    {
        return PedidoProducto::query()->where('pedido_id', '=', $this->id);
    }
    
    public function getEstadoBadge(): string
    {
        $badges = [
            self::ESTADO_PENDIENTE => 'warning',
            self::ESTADO_CONFIRMADO => 'info',
            self::ESTADO_ENVIADO => 'primary',
            self::ESTADO_ENTREGADO => 'success',
            self::ESTADO_CANCELADO => 'danger',
        ];
        return $badges[$this->estado] ?? 'secondary';
    }
}