<?php

namespace Shared\Models;

use Core\Model;

/**
 * Producto - Modelo de producto/Pokémon
 */
class Producto extends Model
{
    protected static string $table = 'productos';
    protected static string $primaryKey = 'id';
    
    protected static array $fillable = [
        'nombrePro', 'precioPro', 'cantidadPro', 'categoria', 
        'urlFoto', 'estado', 'usuarioCreacion', 'usuarioModificacion',
        'fechaModificacion'
    ];
    
    protected static array $casts = [
        'id' => 'int',
        'precioPro' => 'float',
        'cantidadPro' => 'int',
        'categoria' => 'int',
        'usuarioCreacion' => 'int',
        'usuarioModificacion' => 'int',
        'fechaModificacion' => 'datetime',
        'estado' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public const ESTADO_ACTIVO = 'A';
    public const ESTADO_INACTIVO = 'I';
    
    // ===== Relaciones =====
    
    public function categoria(): ?Categoria
    {
        return Categoria::find($this->categoria);
    }
    
    public function creador(): ?Usuario
    {
        return Usuario::find($this->usuarioCreacion);
    }
    
    // ===== Scopes =====
    
    public static function active(): \Core\QueryBuilder
    {
        return static::query()
            ->where('productos.estado', '=', self::ESTADO_ACTIVO)
            ->leftJoin('categorias', 'productos.categoria', '=', 'categorias.id')
            ->select(['productos.*', 'categorias.nombreCat as categoria_nombre']);
    }
    
    public static function byCategory(int $categoryId): \Core\QueryBuilder
    {
        return static::active()->where('productos.categoria', '=', $categoryId);
    }
    
    public static function search(string $term): \Core\QueryBuilder
    {
        return static::active()
            ->where('productos.nombrePro', 'LIKE', "%{$term}%");
    }
    
    public static function lowStock(int $threshold = 5): \Core\QueryBuilder
    {
        return static::active()->where('productos.cantidadPro', '<=', $threshold);
    }
    
    // ===== Helpers =====
    
    public function isAvailable(int $quantity = 1): bool
    {
        return $this->estado === self::ESTADO_ACTIVO && $this->cantidadPro >= $quantity;
    }
    
    public function reduceStock(int $quantity): bool
    {
        if (!$this->isAvailable($quantity)) return false;
        
        $this->cantidadPro -= $quantity;
        return $this->save();
    }
    
    public function increaseStock(int $quantity): bool
    {
        $this->cantidadPro += $quantity;
        return $this->save();
    }
    
    // ===== Serialización =====
    
    public function toArray(): array
    {
        $array = parent::toArray();
        
        // Agregar nombre de categoría si está cargada
        if (isset($this->attributes['categoria']) && is_string($this->attributes['categoria'])) {
            $array['categoria_nombre'] = $this->attributes['categoria'];
        }
        
        return $array;
    }
}