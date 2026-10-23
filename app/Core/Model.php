<?php

namespace Core;

/**
 * Base Model - Active Record pattern simple con Query Builder
 */
abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    protected static array $hidden = ['password', 'remember_token'];
    protected static array $casts = [];
    protected static array $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    protected array $attributes = [];
    protected array $original = [];
    protected array $changes = [];
    protected bool $exists = false;
    
    // Timestamps
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    protected bool $timestamps = true;
    
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->original = $this->attributes;
    }
    
    // ===== Acceso a atributos =====
    
    public function __get(string $key): mixed
    {
        return $this->getAttribute($key);
    }
    
    public function __set(string $key, mixed $value): void
    {
        $this->setAttribute($key, $value);
    }
    
    public function __isset(string $key): bool
    {
        return isset($this->attributes[$key]) || $this->hasGetMutator($key);
    }
    
    public function getAttribute(string $key): mixed
    {
        // Mutator (get{X}Attribute)
        if ($this->hasGetMutator($key)) {
            return $this->{'get' . ucfirst($key) . 'Attribute'}($this->attributes[$key] ?? null);
        }
        
        $value = $this->attributes[$key] ?? null;
        
        // Casting
        if (isset(static::$casts[$key])) {
            $value = $this->castAttribute($key, $value);
        }
        
        return $value;
    }
    
    public function setAttribute(string $key, mixed $value): void
    {
        // Mutator (set{X}Attribute)
        if ($this->hasSetMutator($key)) {
            $value = $this->{'set' . ucfirst($key) . 'Attribute'}($value);
        }
        
        // Detectar cambios
        if (!isset($this->attributes[$key]) || $this->attributes[$key] !== $value) {
            $this->changes[$key] = $value;
        }
        
        $this->attributes[$key] = $value;
    }
    
    protected function hasGetMutator(string $key): bool
    {
        return method_exists($this, 'get' . ucfirst($key) . 'Attribute');
    }
    
    protected function hasSetMutator(string $key): bool
    {
        return method_exists($this, 'set' . ucfirst($key) . 'Attribute');
    }
    
    protected function castAttribute(string $key, mixed $value): mixed
    {
        $cast = static::$casts[$key];
        
        return match ($cast) {
            'int', 'integer' => (int)$value,
            'real', 'float', 'double' => (float)$value,
            'bool', 'boolean' => (bool)$value,
            'array', 'json' => is_string($value) ? json_decode($value, true) : $value,
            'object' => is_string($value) ? json_decode($value) : $value,
            'date', 'datetime' => $value ? new \DateTime($value) : null,
            'timestamp' => $value ? (int)$value : null,
            default => $value,
        };
    }
    
    // ===== Fillable / Guarded =====
    
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }
    
    protected function isFillable(string $key): bool
    {
        return in_array($key, static::$fillable) || static::$fillable === ['*'];
    }
    
    public function forceFill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }
    
    // ===== Query Builder =====
    
    public static function query(): \Core\QueryBuilder
    {
        return db()->table(static::$table);
    }
    
    public static function find(mixed $id): ?static
    {
        return static::query()->where(static::$primaryKey, '=', $id)->first() 
            ? static::hydrate(static::query()->where(static::$primaryKey, '=', $id)->first()) 
            : null;
    }
    
    public static function findOrFail(mixed $id): static
    {
        $model = static::find($id);
        if (!$model) {
            throw new \RuntimeException(static::class . " not found with id: {$id}");
        }
        return $model;
    }
    
    public static function first(): ?static
    {
        $result = static::query()->first();
        return $result ? static::hydrate($result) : null;
    }
    
    public static function all(): array
    {
        return array_map([static::class, 'hydrate'], static::query()->get());
    }
    
    public static function get(array $columns = ['*']): array
    {
        return static::query()->select($columns)->get(static::class);
    }
    
    public static function where(string $column, string $operator, mixed $value, string $boolean = 'and'): \Core\QueryBuilder
    {
        return static::query()->where($column, $operator, $value, $boolean);
    }
    
    public static function paginate(int $perPage = 15, int $page = 1): array
    {
        $result = static::query()->paginate($perPage, $page);
        $result['data'] = array_map([static::class, 'hydrate'], $result['data']);
        return $result;
    }
    
    // ===== Hydration =====
    
    public static function hydrate(array $data): static
    {
        $model = new static();
        $model->exists = true;
        $model->attributes = $data;
        $model->original = $data;
        $model->changes = [];
        return $model;
    }
    
    // ===== Persistencia =====
    
    public function save(): bool
    {
        if ($this->exists) {
            return $this->update();
        }
        return $this->insert();
    }
    
    protected function insert(): bool
    {
        if ($this->timestamps) {
            $this->{$this->getCreatedAtColumn()} = date('Y-m-d H:i:s');
            $this->{$this->getUpdatedAtColumn()} = date('Y-m-d H:i:s');
        }
        
        $attributes = $this->getDirty();
        $id = db()->insert(static::$table, $attributes);
        
        $this->{$this->getKeyName()} = $id;
        $this->exists = true;
        $this->original = $this->attributes;
        $this->changes = [];
        
        return true;
    }
    
    protected function update(): bool
    {
        if ($this->timestamps) {
            $this->{$this->getUpdatedAtColumn()} = date('Y-m-d H:i:s');
        }
        
        $dirty = $this->getDirty();
        if (empty($dirty)) return true;
        
        $key = $this->getKeyName();
        $keyValue = $this->$key;
        
        db()->update(static::$table, $dirty, "{$key} = ?", [$keyValue]);
        
        $this->original = $this->attributes;
        $this->changes = [];
        
        return true;
    }
    
    public function delete(): bool
    {
        if (!$this->exists) return false;
        
        $key = $this->getKeyName();
        $keyValue = $this->$key;
        
        $result = db()->delete(static::$table, "{$key} = ?", [$keyValue]);
        
        $this->exists = false;
        return $result > 0;
    }
    
    // ===== Dirty tracking =====
    
    public function getDirty(): array
    {
        return $this->changes;
    }
    
    public function isDirty(string $attribute = null): bool
    {
        if ($attribute) {
            return isset($this->changes[$attribute]);
        }
        return !empty($this->changes);
    }
    
    public function getOriginal(string $key = null, mixed $default = null): mixed
    {
        if ($key) {
            return $this->original[$key] ?? $default;
        }
        return $this->original;
    }
    
    public function wasChanged(string $attribute = null): bool
    {
        if ($attribute) {
            return isset($this->original[$attribute]) && $this->original[$attribute] !== $this->attributes[$attribute];
        }
        return $this->original !== $this->attributes;
    }
    
    // ===== Serialización =====
    
    public function toArray(): array
    {
        $attributes = $this->attributes;
        
        // Ocultar campos sensibles
        foreach (static::$hidden as $hidden) {
            unset($attributes[$hidden]);
        }
        
        // Aplicar casts para salida
        foreach ($attributes as $key => $value) {
            if (isset(static::$casts[$key])) {
                $attributes[$key] = $this->castAttribute($key, $value);
            }
        }
        
        return $attributes;
    }
    
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
    public function __toString(): string
    {
        return $this->toJson();
    }
    
    // ===== Helpers =====
    
    public function getKey(): mixed
    {
        return $this->{$this->getKeyName()};
    }
    
    public function getKeyName(): string
    {
        return static::$primaryKey;
    }
    
    public function getCreatedAtColumn(): string
    {
        return static::CREATED_AT;
    }
    
    public function getUpdatedAtColumn(): string
    {
        return static::UPDATED_AT;
    }
    
    public function fresh(): ?static
    {
        return static::find($this->getKey());
    }
    
    public function refresh(): static
    {
        $fresh = $this->fresh();
        if ($fresh) {
            $this->exists = true;
            $this->attributes = $fresh->attributes;
            $this->original = $fresh->original;
            $this->changes = [];
        }
        return $this;
    }
    
    public static function create(array $attributes): static
    {
        $model = new static($attributes);
        $model->save();
        return $model;
    }
    
    public static function updateOrCreate(array $attributes, array $values = []): static
    {
        $model = static::query()->where(...array_slice($attributes, 0, 3))->first();
        
        if ($model) {
            $model->fill($values)->save();
            return static::hydrate($model->toArray());
        }
        
        return static::create(array_merge($attributes, $values));
    }
    
    public function replicate(array $except = []): static
    {
        $attributes = $this->toArray();
        foreach (array_merge([static::$primaryKey], $except) as $key) {
            unset($attributes[$key]);
        }
        return new static($attributes);
    }
}

/**
 * Helper global para DB
 */
function db(): \Core\Database
{
    return \Core\Database::getInstance(config('database.connections.' . config('database.default')));
}