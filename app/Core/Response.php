<?php

namespace Core;

/**
 * Response - Wrapper para respuestas HTTP
 */
class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private mixed $content = '';
    private string $contentType = 'text/html; charset=utf-8';
    
    public function __construct(mixed $content = '', int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }
    
    // ===== Factory methods =====
    
    public static function make(mixed $content = '', int $statusCode = 200, array $headers = []): self
    {
        return new self($content, $statusCode, $headers);
    }
    
    public static function json(mixed $data, int $statusCode = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        return new self(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $statusCode, $headers);
    }
    
    public static function view(string $view, array $data = [], int $statusCode = 200, array $headers = []): self
    {
        $content = view($view, $data);
        return new self($content, $statusCode, $headers);
    }
    
    public static function redirect(string $url, int $statusCode = 302): self
    {
        return new self('', $statusCode, ['Location' => $url]);
    }
    
    public static function back(int $statusCode = 302): self
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        return self::redirect($referer, $statusCode);
    }
    
    public static function download(string $path, string $name = null, array $headers = []): self
    {
        $name ??= basename($path);
        $headers['Content-Disposition'] = "attachment; filename=\"{$name}\"";
        $headers['Content-Type'] = mime_content_type($path) ?? 'application/octet-stream';
        $headers['Content-Length'] = filesize($path);
        
        $response = new self('', 200, $headers);
        $response->setFile($path);
        return $response;
    }
    
    public static function error(string $message, int $statusCode = 500): self
    {
        return self::json(['error' => $message], $statusCode);
    }
    
    public static function notFound(string $message = 'Recurso no encontrado'): self
    {
        return self::error($message, 404);
    }
    
    public static function forbidden(string $message = 'Acceso denegado'): self
    {
        return self::error($message, 403);
    }
    
    public static function unauthorized(string $message = 'No autorizado'): self
    {
        return self::error($message, 401);
    }
    
    // ===== Setters =====
    
    public function setContent(mixed $content): self
    {
        $this->content = $content;
        return $this;
    }
    
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }
    
    public function setHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }
    
    public function setHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }
    
    public function setContentType(string $type): self
    {
        $this->contentType = $type;
        $this->headers['Content-Type'] = $type;
        return $this;
    }
    
    public function setFile(string $path): self
    {
        $this->content = $path;
        return $this;
    }
    
    // ===== Cookies =====
    
    public function withCookie(string $name, string $value, int $minutes = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = true, string $samesite = 'lax'): self
    {
        $cookie = [
            'name' => $name,
            'value' => $value,
            'expire' => $minutes > 0 ? time() + ($minutes * 60) : 0,
            'path' => $path,
            'domain' => $domain ?: '',
            'secure' => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite,
        ];
        
        $this->headers['Set-Cookie'][] = $this->formatCookie($cookie);
        return $this;
    }
    
    public function withoutCookie(string $name): self
    {
        return $this->withCookie($name, '', -1);
    }
    
    private function formatCookie(array $cookie): string
    {
        $parts = ["{$cookie['name']}={$cookie['value']}"];
        
        if ($cookie['expire']) {
            $parts[] = 'Expires=' . gmdate('D, d-M-Y H:i:s T', $cookie['expire']);
        }
        
        $parts[] = "Path={$cookie['path']}";
        
        if ($cookie['domain']) {
            $parts[] = "Domain={$cookie['domain']}";
        }
        
        if ($cookie['secure']) {
            $parts[] = 'Secure';
        }
        
        if ($cookie['httponly']) {
            $parts[] = 'HttpOnly';
        }
        
        $parts[] = "SameSite={$cookie['samesite']}";
        
        return implode('; ', $parts);
    }
    
    // ===== Envío =====
    
    public function send(): void
    {
        // Enviar status code
        http_response_code($this->statusCode);
        
        // Enviar headers
        foreach ($this->headers as $key => $values) {
            $values = (array)$values;
            foreach ($values as $value) {
                header("{$key}: {$value}", false, $this->statusCode);
            }
        }
        
        // Content-Type por defecto si no se envió
        if (!isset($this->headers['Content-Type'])) {
            header("Content-Type: {$this->contentType}");
        }
        
        // Enviar contenido
        if (is_string($this->content) && file_exists($this->content)) {
            // Archivo para descarga
            readfile($this->content);
        } else {
            echo $this->content;
        }
    }
    
    // ===== Getters =====
    
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    public function getContent(): mixed
    {
        return $this->content;
    }
    
    public function __toString(): string
    {
        return (string)$this->content;
    }
}