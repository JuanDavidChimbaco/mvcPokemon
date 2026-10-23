<?php

namespace Shared\Models;

use Core\Model;

/**
 * Categoria - Modelo de categoría/tipo de Pokémon
 */
class Categoria extends Model
{
    protected static string $table = 'categorias';
    protected static string $primaryKey = 'id';
    
    protected static array $fillable = ['nombreCat', 'descripcion', 'estado'];
    
    protected static array $casts = [
        'id' => 'int',
        'estado' => 'string',
    ];
    
    public const ESTADO_ACTIVO = 'A';
    public const ESTADO_INACTIVO = 'I';
    
    // ===== Relaciones =====
    
    public function productos(): \Core\QueryBuilder
    {
        return Producto::byCategory($this->id);
    }
    
    public function productosCount(): int
    {
        return $this->productos()->count();
    }
    
    // ===== Scopes =====
    
    public static function active(): \Core\QueryBuilder
    {
        return static::query()->where('estado', '=', self::ESTADO_ACTIVO);
    }
    
    public static function ordered(): \Core\QueryBuilder
    {
        return static::active()->orderBy('nombreCat', 'asc');
    }
    
    public static function orderedGet(array $columns = ['*']): array
    {
        return static::ordered()->select($columns)->get(static::class);
    }
    
    // ===== Iconos/Imágenes por tipo =====
    
    private static array $typeIcons = [
        'normal' => 'normal.png',
        'fighting' => 'fighting.png',
        'flying' => 'flying.png',
        'poison' => 'poison.png',
        'ground' => 'ground.png',
        'rock' => 'rock.png',
        'bug' => 'bug.png',
        'ghost' => 'ghost.png',
        'steel' => 'steel.png',
        'fire' => 'fire.png',
        'water' => 'water.png',
        'grass' => 'grass.png',
        'electric' => 'electric.png',
        'psychic' => 'psychic.png',
        'ice' => 'ice.png',
        'dragon' => 'dragon.png',
        'dark' => 'dark.png',
        'fairy' => 'fairy.png',
    ];
    
    private static array $typeColors = [
        'normal' => '#A8A878',
        'fighting' => '#C03028',
        'flying' => '#A890F0',
        'poison' => '#A040A0',
        'ground' => '#E0C068',
        'rock' => '#B8A038',
        'bug' => '#A8B820',
        'ghost' => '#705898',
        'steel' => '#B8B8D0',
        'fire' => '#F08030',
        'water' => '#6890F0',
        'grass' => '#78C850',
        'electric' => '#F8D030',
        'psychic' => '#F85888',
        'ice' => '#98D8D8',
        'dragon' => '#7038F8',
        'dark' => '#705848',
        'fairy' => '#EE99AC',
    ];
    
    public function getIcon(): string
    {
        $slug = strtolower(str_replace(' ', '_', $this->nombreCat));
        return self::$typeIcons[$slug] ?? 'unknown.png';
    }
    
    public function getIconUrl(): string
    {
        return asset('img/types/' . $this->getIcon());
    }
    
    public function getColor(): string
    {
        $slug = strtolower(str_replace(' ', '_', $this->nombreCat));
        return self::$typeColors[$slug] ?? '#6C757D';
    }
    
    public function getGradient(): string
    {
        $color = $this->getColor();
        // Generar versión más oscura para gradiente
        $dark = $this->darkenColor($color, 20);
        return "linear-gradient(135deg, {$color}, {$dark})";
    }
    
    private function darkenColor(string $hex, int $percent): string
    {
        $hex = ltrim($hex, '#');
        $rgb = array_map('hexdec', str_split($hex, 2));
        $rgb = array_map(fn($c) => max(0, $c - round($c * $percent / 100)), $rgb);
        return '#' . implode('', array_map(fn($c) => str_pad(dechex($c), 2, '0', STR_PAD_LEFT), $rgb));
    }
}