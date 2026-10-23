<?php

namespace Core;

/**
 * Cache simple basado en archivos
 */
class Cache
{
    private string $path;
    private int $defaultTtl;
    
    public function __construct(string $path = null, int $defaultTtl = 3600)
    {
        $this->path = $path ?? config('cache.path') ?? sys_get_temp_dir() . '/mvc_cache';
        $this->defaultTtl = $defaultTtl;
        
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }
    
    public function get(string $key, mixed $default = null): mixed
    {
        $file = $this->path . '/' . md5($key) . '.cache';
        
        if (!file_exists($file)) {
            return $default;
        }
        
        $data = @unserialize(file_get_contents($file));
        
        if ($data === false || time() > $data['expires']) {
            @unlink($file);
            return $default;
        }
        
        return $data['value'];
    }
    
    public function set(string $key, mixed $value, int $ttl = null): bool
    {
        $ttl ??= $this->defaultTtl;
        $file = $this->path . '/' . md5($key) . '.cache';
        
        $data = [
            'value' => $value,
            'expires' => time() + $ttl,
        ];
        
        return @file_put_contents($file, serialize($data)) !== false;
    }
    
    public function has(string $key): bool
    {
        return $this->get($key, null) !== null;
    }
    
    public function forget(string $key): bool
    {
        $file = $this->path . '/' . md5($key) . '.cache';
        return @unlink($file);
    }
    
    public function flush(): void
    {
        foreach (glob($this->path . '/*.cache') as $file) {
            @unlink($file);
        }
    }
    
    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        if ($this->has($key)) {
            return $this->get($key);
        }
        
        $value = $callback();
        $this->set($key, $value, $ttl);
        return $value;
    }
}