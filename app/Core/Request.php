<?php

namespace Core;

/**
 * Request - Wrapper seguro para $_GET, $_POST, $_FILES, $_SERVER
 */
class Request
{
    private array $query = [];
    private array $request = [];
    private array $files = [];
    private array $server = [];
    private array $headers = [];
    private ?string $content = null;
    private ?array $json = null;
    private array $routeParams = [];
    
    public function __construct()
    {
        $this->query = $_GET;
        $this->request = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        $this->parseHeaders();
        $this->parseJsonBody();
        $this->routeParams = $_SERVER['ROUTE_PARAMS'] ?? [];
    }
    
    private function parseHeaders(): void
    {
        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $header = strtolower(str_replace('_', '-', substr($key, 5)));
                $this->headers[$header] = $value;
            } elseif (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH'])) {
                $header = strtolower(str_replace('_', '-', $key));
                $this->headers[$header] = $value;
            }
        }
    }
    
    private function parseJsonBody(): void
    {
        if ($this->isJson()) {
            $input = file_get_contents('php://input');
            $this->json = json_decode($input, true) ?? [];
            $this->content = $input;
        }
    }
    
    // ===== Acceso a datos =====
    
    public function all(): array
    {
        return array_merge($this->query, $this->request, $this->json ?? []);
    }
    
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->request[$key] ?? $this->query[$key] ?? $this->json[$key] ?? $this->routeParams[$key] ?? $default;
    }
    
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }
    
    public function post(string $key, mixed $default = null): mixed
    {
        return $this->request[$key] ?? $default;
    }
    
    public function json(string $key = null, mixed $default = null): mixed
    {
        if ($key) {
            return $this->json[$key] ?? $default;
        }
        return $this->json ?? [];
    }
    
    public function route(string $key, mixed $default = null): mixed
    {
        return $this->routeParams[$key] ?? $default;
    }
    
    public function param(string $key, mixed $default = null): mixed
    {
        return $this->route($key, $default);
    }
    
    public function has(string $key): bool
    {
        return isset($this->request[$key]) || isset($this->query[$key]) || isset($this->json[$key]) || isset($this->routeParams[$key]);
    }
    
    public function filled(string $key): bool
    {
        $value = $this->input($key);
        return $value !== null && $value !== '';
    }
    
    public function only(array $keys): array
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }
    
    public function except(array $keys): array
    {
        return array_diff_key($this->all(), array_flip($keys));
    }
    
    // ===== Files =====
    
    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }
    
    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }
    
    public function allFiles(): array
    {
        return $this->files;
    }
    
    // ===== Headers =====
    
    public function header(string $key, mixed $default = null): mixed
    {
        return $this->headers[strtolower($key)] ?? $default;
    }
    
    public function bearerToken(): ?string
    {
        $header = $this->header('authorization');
        if ($header && preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
            return $matches[1];
        }
        return null;
    }
    
    // ===== Método / URL =====
    
    public function method(): string
    {
        $method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
        if ($method === 'POST') {
            $override = strtoupper((string)($this->request['_method'] ?? ''));
            if (in_array($override, ['PUT', 'PATCH', 'DELETE'], true)) {
                return $override;
            }
        }

        return $method;
    }
    
    public function isMethod(string $method): bool
    {
        return strtoupper($this->method()) === strtoupper($method);
    }
    
    public function url(): string
    {
        $scheme = $this->isSecure() ? 'https' : 'http';
        $host = $this->server['HTTP_HOST'] ?? $this->server['SERVER_NAME'] ?? 'localhost';
        $uri = $this->server['REQUEST_URI'] ?? '/';
        return "{$scheme}://{$host}{$uri}";
    }
    
    public function path(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        return parse_url($uri, PHP_URL_PATH) ?? '/';
    }
    
    public function fullUrl(): string
    {
        $query = $this->server['QUERY_STRING'] ?? '';
        return $query ? $this->url() . '?' . $query : $this->url();
    }
    
    public function isSecure(): bool
    {
        return (!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off')
            || ($this->server['SERVER_PORT'] ?? 80) == 443
            || ($this->header('x-forwarded-proto') ?? '') === 'https';
    }
    
    public function ip(): string
    {
        return $this->header('x-forwarded-for')
            ?? $this->header('x-real-ip')
            ?? $this->server['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    public function userAgent(): string
    {
        return $this->server['HTTP_USER_AGENT'] ?? '';
    }
    
    public function isAjax(): bool
    {
        return $this->header('x-requested-with') === 'XMLHttpRequest';
    }
    
    public function isJson(): bool
    {
        return str_contains($this->header('content-type', ''), 'application/json');
    }
    
    // ===== Validación simple =====
    
    public function validate(array $rules): array
    {
        $errors = [];
        $validated = [];
        
        foreach ($rules as $field => $rule) {
            $value = $this->input($field);
            $ruleErrors = $this->validateField($field, $value, $rule);
            
            if ($ruleErrors) {
                $errors[$field] = $ruleErrors;
            } else {
                $validated[$field] = $value;
            }
        }
        
        if ($errors) {
            throw new ValidationException($errors);
        }
        
        return $validated;
    }
    
    private function validateField(string $field, mixed $value, string $rule): array
    {
        $errors = [];
        $rules = explode('|', $rule);
        
        foreach ($rules as $rule) {
            [$ruleName, $param] = array_pad(explode(':', $rule, 2), 2, null);
            
            switch ($ruleName) {
                case 'required':
                    if ($value === null || $value === '') {
                        $errors[] = "El campo {$field} es obligatorio";
                    }
                    break;
                    
                case 'email':
                    if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "El campo {$field} debe ser un email válido";
                    }
                    break;
                    
                case 'string':
                    if ($value !== null && !is_string($value)) {
                        $errors[] = "El campo {$field} debe ser texto";
                    }
                    break;

                case 'boolean':
                    if ($value !== null && !in_array($value, [true, false, 0, 1, '0', '1'], true)) {
                        $errors[] = "El campo {$field} debe ser verdadero o falso";
                    }
                    break;
                    
                case 'numeric':
                    if ($value !== null && !is_numeric($value)) {
                        $errors[] = "El campo {$field} debe ser numérico";
                    }
                    break;
                    
                case 'integer':
                    if ($value !== null && filter_var($value, FILTER_VALIDATE_INT) === false) {
                        $errors[] = "El campo {$field} debe ser un entero";
                    }
                    break;
                    
                case 'min':
                    if ($value !== null) {
                        $length = is_string($value) ? strlen($value) : (is_array($value) ? count($value) : $value);
                        if ($length < (int)$param) {
                            $errors[] = "El campo {$field} debe tener al menos {$param} caracteres";
                        }
                    }
                    break;
                    
                case 'max':
                    if ($value !== null) {
                        $length = is_string($value) ? strlen($value) : (is_array($value) ? count($value) : $value);
                        if ($length > (int)$param) {
                            $errors[] = "El campo {$field} no puede exceder {$param} caracteres";
                        }
                    }
                    break;
                    
                case 'between':
                    [$min, $max] = explode(',', $param);
                    if ($value !== null) {
                        $length = is_string($value) ? strlen($value) : (is_array($value) ? count($value) : $value);
                        if ($length < (int)$min || $length > (int)$max) {
                            $errors[] = "El campo {$field} debe estar entre {$min} y {$max} caracteres";
                        }
                    }
                    break;
                    
                case 'in':
                    $values = explode(',', $param);
                    if ($value !== null && !in_array($value, $values)) {
                        $errors[] = "El campo {$field} debe ser uno de: " . implode(', ', $values);
                    }
                    break;
                    
                case 'unique':
                    if ($value !== null && $value !== '') {
                        [$table, $column, $except, $exceptColumn] = array_pad(explode(',', (string)$param), 4, null);
                        $exceptColumn ??= 'id';

                        foreach ([$table, $column, $exceptColumn] as $identifier) {
                            if (!is_string($identifier) || !preg_match('/\A[A-Za-z_][A-Za-z0-9_]*\z/', $identifier)) {
                                throw new \InvalidArgumentException("Regla unique inválida para {$field}");
                            }
                        }

                        $sql = "SELECT 1 FROM `{$table}` WHERE `{$column}` = :value";
                        $params = ['value' => $value];

                        if ($except !== null && $except !== '') {
                            $sql .= " AND `{$exceptColumn}` != :except";
                            $params['except'] = $except;
                        }

                        if (app()->database()->selectOne($sql . ' LIMIT 1', $params)) {
                            $errors[] = "El campo {$field} ya está en uso";
                        }
                    }
                    break;
                    
                case 'confirmed':
                    if ($value !== $this->input($field . '_confirmation')) {
                        $errors[] = "La confirmación de {$field} no coincide";
                    }
                    break;
            }
        }
        
        return $errors;
    }
}

/**
 * Excepción de validación
 */
class ValidationException extends \Exception
{
    public array $errors;
    
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Validation failed', 422);
    }
}