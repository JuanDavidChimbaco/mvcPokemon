<?php

namespace Shared\Models;

use Core\Model;

/**
 * Rol - Modelo de roles de usuario
 */
class Rol extends Model
{
    protected static string $table = 'roles';
    protected static string $primaryKey = 'id';
    
    protected static array $fillable = ['nombreRol', 'descripcion', 'estado'];
    
    protected static array $casts = [
        'id' => 'int',
        'estado' => 'string',
    ];
    
    public const ESTADO_ACTIVO = 'A';
    public const ESTADO_INACTIVO = 'I';
    
    // ===== Roles del sistema =====
    
    public const SUPER_ADMIN = 'SuperAdmin';
    public const ADMIN = 'Administrador';
    public const MANAGER = 'Gerente';
    public const VENDEDOR = 'Vendedor';
    public const CLIENTE = 'Cliente';
    
    // ===== Relaciones =====
    
    public function usuarios(): \Core\QueryBuilder
    {
        return Usuario::query()->where('idRol', '=', $this->id);
    }
    
    // ===== Scopes =====
    
    public static function active(): \Core\QueryBuilder
    {
        return static::query()->where('estado', '=', self::ESTADO_ACTIVO);
    }

    public static function ordered(): \Core\QueryBuilder
    {
        return static::active()->orderBy('nombreRol', 'asc');
    }
    
    // ===== Permisos simples =====
    
    private static array $permissions = [
        self::SUPER_ADMIN => ['*'],
        self::ADMIN => [
            'admin.*',
            'products.*',
            'categories.*',
            'users.*',
            'roles.*',
            'orders.*',
            'purchases.*',
            'taxes.*',
        ],
        self::MANAGER => [
            'products.view',
            'products.create',
            'products.edit',
            'categories.view',
            'categories.create',
            'orders.view',
            'orders.edit',
            'purchases.view',
        ],
        self::VENDEDOR => [
            'products.view',
            'orders.view',
            'orders.create',
        ],
        self::CLIENTE => [
            'shop.*',
            'cart.*',
            'orders.own',
        ],
    ];
    
    public function hasPermission(string $permission): bool
    {
        $perms = self::$permissions[$this->nombreRol] ?? [];
        
        if (in_array('*', $perms)) return true;
        if (in_array($permission, $perms)) return true;
        
        // Wildcard support (ej: products.*)
        foreach ($perms as $perm) {
            if (str_ends_with($perm, '.*')) {
                $prefix = rtrim($perm, '.*');
                if (str_starts_with($permission, $prefix . '.')) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public static function getDefaultRole(): string
    {
        return self::CLIENTE;
    }
}