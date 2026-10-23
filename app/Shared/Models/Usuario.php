<?php

namespace Shared\Models;

use Core\Model;

/**
 * Usuario - Modelo de usuario con password hashing
 */
class Usuario extends Model
{
    protected static string $table = 'usuarios';
    protected static string $primaryKey = 'id';
    
    protected static array $fillable = [
        'tipoDoc', 'identificacion', 'nombre', 'apellido', 
        'correo', 'direccion', 'telefono', 'genero', 
        'password', 'idRol', 'estado'
    ];
    
    protected static array $hidden = ['password', 'remember_token'];
    
    protected static array $casts = [
        'id' => 'int',
        'idRol' => 'int',
        'estado' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public const ESTADO_ACTIVO = 'A';
    public const ESTADO_INACTIVO = 'I';
    
    // ===== Mutators =====
    
    public function setPasswordAttribute(string $value): string
    {
        return password_hash($value, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
    
    // ===== Scopes =====
    
    public static function active(): \Core\QueryBuilder
    {
        return static::query()->where('estado', '=', self::ESTADO_ACTIVO);
    }
    
    public static function byEmail(string $email): \Core\QueryBuilder
    {
        return static::query()->where('correo', '=', $email);
    }
    
    // ===== Auth =====
    
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
    
    public static function authenticate(string $email, string $password): ?static
    {
        $attributes = static::active()->where('correo', '=', $email)->first();
        $user = $attributes ? static::hydrate($attributes) : null;
        
        if ($user && $user->verifyPassword($password)) {
            return $user;
        }
        
        return null;
    }
    
    // ===== Roles =====
    
    public function rol(): ?Rol
    {
        return Rol::find($this->idRol);
    }
    
    public function hasRole(string|array $roles): bool
    {
        $rol = $this->rol();
        if (!$rol) return false;
        
        $roles = (array)$roles;
        return in_array($rol->nombreRol, $roles);
    }
    
    public function isAdmin(): bool
    {
        return $this->hasRole(['Administrador', 'Admin', 'SuperAdmin']);
    }
    
    // ===== Serialización =====
    
    public function toArray(): array
    {
        $array = parent::toArray();
        $array['nombre_completo'] = $this->nombre_completo;
        return $array;
    }
}