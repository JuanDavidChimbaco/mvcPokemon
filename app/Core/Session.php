<?php

namespace Core;

/**
 * Session - Wrapper seguro para sesiones PHP
 */
class Session
{
    private bool $started = false;
    private array $flash = [];
    private array $oldInput = [];
    
    public function __construct(array $config = [])
    {
        $this->configure($config);
    }
    
    private function configure(array $config): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $this->started = true;
            return;
        }
        
        // Configuración segura
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_samesite', $config['samesite'] ?? 'lax');
        
        $secure = filter_var($config['secure'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $https = filter_var($_SERVER['HTTPS'] ?? false, FILTER_VALIDATE_BOOLEAN)
            || (int)($_SERVER['SERVER_PORT'] ?? 0) === 443;
        ini_set('session.cookie_secure', $secure || $https ? '1' : '0');
        
        if ($config['domain'] ?? null) {
            ini_set('session.cookie_domain', $config['domain']);
        }
        
        ini_set('session.cookie_path', $config['path'] ?? '/');
        ini_set('session.gc_maxlifetime', (string)($config['lifetime'] ?? 120 * 60));
        ini_set('session.save_path', $config['files'] ?? sys_get_temp_dir());
        
        session_name($config['cookie'] ?? 'app_session');
    }
    
    public function start(): bool
    {
        if ($this->started) return true;
        
        $started = session_start();
        $this->started = $started;
        
        if ($started) {
            $this->migrateFlash();
            $this->migrateOldInput();
            $this->regenerateIfNeeded();
        }
        
        return $started;
    }
    
    private function regenerateIfNeeded(): void
    {
        // Regenerar ID cada 30 minutos por seguridad
        $lastRegeneration = $_SESSION['_regenerated'] ?? 0;
        if (time() - $lastRegeneration > 1800) {
            $this->regenerate();
        }
    }
    
    public function regenerate(bool $deleteOld = true): bool
    {
        if (!$this->started) $this->start();
        
        $result = session_regenerate_id($deleteOld);
        $_SESSION['_regenerated'] = time();
        return $result;
    }
    
    // ===== Flash data (solo siguiente request) =====
    
    private function migrateFlash(): void
    {
        // Mover _flash_new a _flash y limpiar _flash_new
        if (isset($_SESSION['_flash_new'])) {
            $_SESSION['_flash'] = $_SESSION['_flash_new'];
            unset($_SESSION['_flash_new']);
        }
    }
    
    public function flash(string $key, mixed $value): void
    {
        $this->start();
        $_SESSION['_flash_new'][$key] = $value;
    }
    
    public function getFlash(string $key, mixed $default = null): mixed
    {
        $this->start();
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }
    
    public function hasFlash(string $key): bool
    {
        $this->start();
        return isset($_SESSION['_flash'][$key]);
    }
    
    public function keepFlash(string $key): void
    {
        $this->start();
        if (isset($_SESSION['_flash'][$key])) {
            $_SESSION['_flash_new'][$key] = $_SESSION['_flash'][$key];
        }
    }
    
    public function allFlash(): array
    {
        $this->start();
        $flash = $_SESSION['_flash'] ?? [];
        $_SESSION['_flash'] = [];
        return $flash;
    }
    
    // ===== Old input (repoblar formularios) =====
    
    private function migrateOldInput(): void
    {
        if (isset($_SESSION['_old_input_new'])) {
            $_SESSION['_old_input'] = $_SESSION['_old_input_new'];
            unset($_SESSION['_old_input_new']);
        } else {
            unset($_SESSION['_old_input']);
        }
    }
    
    public function flashInput(array $input = null): void
    {
        $this->start();
        $_SESSION['_old_input_new'] = $input ?? $_POST;
    }
    
    public function old(string $key, mixed $default = ''): mixed
    {
        $this->start();
        return $_SESSION['_old_input'][$key] ?? $default;
    }
    
    public function hasOld(string $key): bool
    {
        $this->start();
        return isset($_SESSION['_old_input'][$key]);
    }
    
    // ===== Acceso general =====
    
    public function put(string $key, mixed $value): void
    {
        $this->start();
        $_SESSION[$key] = $value;
    }
    
    public function get(string $key, mixed $default = null): mixed
    {
        $this->start();
        return $_SESSION[$key] ?? $default;
    }
    
    public function has(string $key): bool
    {
        $this->start();
        return isset($_SESSION[$key]);
    }
    
    public function forget(string $key): void
    {
        $this->start();
        unset($_SESSION[$key]);
    }
    
    public function pull(string $key, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);
        $this->forget($key);
        return $value;
    }
    
    public function all(): array
    {
        $this->start();
        // Filtrar internos
        return array_diff_key($_SESSION, array_flip(['_flash', '_flash_new', '_old_input', '_old_input_new', '_regenerated', '_token']));
    }
    
    // ===== Autenticación =====
    
    public function login(mixed $user, array $data = []): void
    {
        $this->start();
        $this->regenerate(true);
        
        $_SESSION['auth'] = [
            'user' => $user,
            'data' => $data,
            'logged_at' => time(),
        ];
    }
    
    public function logout(): void
    {
        $this->start();
        $this->invalidate();
    }
    
    public function user(): mixed
    {
        $this->start();
        return $_SESSION['auth']['user'] ?? null;
    }
    
    public function check(): bool
    {
        $this->start();
        return isset($_SESSION['auth']['user']);
    }
    
    public function guest(): bool
    {
        return !$this->check();
    }
    
    public function id(): ?string
    {
        $this->start();
        return session_id() ?: null;
    }
    
    // ===== CSRF =====
    
    public function token(): string
    {
        $this->start();
        return $_SESSION['_token'] ??= bin2hex(random_bytes(32));
    }
    
    public function verifyToken(string $token): bool
    {
        $this->start();
        return hash_equals($_SESSION['_token'] ?? '', $token);
    }
    
    // ===== Limpieza =====
    
    public function invalidate(): void
    {
        $this->start();
        $_SESSION = [];
        
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        session_destroy();
        $this->started = false;
    }
    
    public function flush(): void
    {
        $this->invalidate();
    }
    
    // ===== Helpers =====
    
    public function isStarted(): bool
    {
        return $this->started || session_status() === PHP_SESSION_ACTIVE;
    }
    
    public function getId(): string
    {
        return session_id();
    }
    
    public function setId(string $id): void
    {
        session_id($id);
    }
}