<?php

require_once __DIR__ . '/Core/Middleware.php';

/**
 * Bootstrap principal - Carga configuración, autoload y servicios base
 */

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'APP_ENV', 'APP_URL']);

// Configurar zona horaria
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'America/Mexico_City');

// Configurar reporte de errores según entorno
if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__ . '/../storage/logs/php-error.log');
}

// Registrar manejador de excepciones global
set_exception_handler(function (Throwable $e) {
    $message = sprintf(
        "[%s] %s in %s:%d\nStack trace:\n%s\n",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );
    
    $logFile = __DIR__ . '/../storage/logs/exceptions.log';
    file_put_contents($logFile, $message, FILE_APPEND);
    
    if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
        echo "<pre>{$message}</pre>";
    } else {
        http_response_code(500);
        echo "Error interno del servidor";
    }
});

// Registrar manejador de errores fatal
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $message = sprintf(
            "[%s] Fatal error: %s in %s:%d\n",
            date('Y-m-d H:i:s'),
            $error['message'],
            $error['file'],
            $error['line']
        );
        file_put_contents(__DIR__ . '/../storage/logs/fatal.log', $message, FILE_APPEND);
    }
});

// Helper functions globales
if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $configFile = __DIR__ . '/config/app.php';
        $value = require $configFile;
        
        // Debug
        error_log("config() called with key='$key', file=$configFile");
        
        foreach ($keys as $k) {
            if (is_array($value) && array_key_exists($k, $value)) {
                $value = $value[$k];
            } else {
                error_log("config() key '$k' not found, returning default");
                return $default;
            }
        }
        return $value;
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        return $value !== false ? $value : $default;
    }
}

if (!function_exists('view')) {
    function view(string $path, array $data = []): string
    {
        $file = str_replace('.', '/', $path) . '.blade.php';
        $fullPath = __DIR__ . "/../resources/views/{$file}";
        
        if (file_exists($fullPath)) {
            return (new \Core\BladeRenderer())->render($path, $data);
        }

        if (!file_exists($fullPath)) {
            // Fallback a .php sin blade
            $fallback = __DIR__ . "/../resources/views/{$path}.php";
            if (file_exists($fallback)) {
                extract($data, EXTR_SKIP);
                ob_start();
                include $fallback;
                return ob_get_clean();
            }
        }

        throw new \RuntimeException("View not found: {$path}");
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, int $status = 302): never
    {
        header("Location: {$url}", true, $status);
        exit;
    }
}

if (!function_exists('json_response')) {
    function json_response(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return app('session')->token();
    }
}

if (!function_exists('csrf_verify')) {
    function csrf_verify(string $token): bool
    {
        return app('session')->verifyToken($token);
    }
}

if (!function_exists('auth')) {
    function auth(): ?\Shared\Models\Usuario
    {
        return app('session')->user();
    }
}

if (!function_exists('request')) {
    function request(?string $key = null, mixed $default = null): mixed
    {
        $request = app('request');
        return $key === null ? $request : $request->input($key, $default);
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed
    {
        return app('session')->old($key, $default);
    }
}

if (!function_exists('flash')) {
    function flash(string $key, string $message = ''): mixed
    {
        if ($message) {
            $_SESSION['_flash'][$key] = $message;
        } else {
            $msg = $_SESSION['_flash'][$key] ?? null;
            unset($_SESSION['_flash'][$key]);
            return $msg;
        }
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'img/')) {
            $image = substr($path, 4);
            if (str_starts_with($image, 'types/')) {
                $image = substr($image, strlen('types/'));
            }
            return rtrim(app()->basePath(), '/') . '/assets_Front/img/' . $image;
        }

        return rtrim(app()->basePath(), '/') . '/assets/' . $path;
    }
}

if (!function_exists('product_image_url')) {
    function product_image_url(?string $image): string
    {
        $image = trim((string)$image);
        if ($image !== '' && filter_var($image, FILTER_VALIDATE_URL)) {
            $url = parse_url($image);
            if (
                ($url['scheme'] ?? '') === 'https'
                && strtolower($url['host'] ?? '') === 'raw.githubusercontent.com'
                && preg_match(
                    '#\A/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/\d+\.png\z#',
                    $url['path'] ?? ''
                )
            ) {
                return $image;
            }
        }

        if (preg_match('#\Auploads/products/([A-Za-z0-9_-]+\.(?:png|jpe?g|webp))\z#i', $image, $matches)) {
            $uploadPath = APP_ROOT . '/public/uploads/products/' . $matches[1];
            if (is_file($uploadPath)) {
                return rtrim(app()->basePath(), '/') . '/uploads/products/' . rawurlencode($matches[1]);
            }
        }

        $filename = basename(str_replace('\\', '/', (string)$image));
        if ($filename !== '' && preg_match('/\A[A-Za-z0-9_.-]+\.(?:png|jpe?g|svg|webp)\z/i', $filename)) {
            $imagePath = APP_ROOT . '/legacy/views/PokeApi/assets_Front/img/' . $filename;
            if (is_file($imagePath)) {
                return rtrim(app()->basePath(), '/') . '/assets_Front/img/' . rawurlencode($filename);
            }
        }

        return asset('img/placeholder.svg');
    }
}

if (!function_exists('route')) {
    function route(string $name, array|int|string $params = []): string
    {
        if (!is_array($params)) {
            $params = ['id' => $params];
        }

        $router = app('router');
        if ($router->hasRoute($name)) {
            $url = $router->getNamedRoute($name)->uri;
            foreach ($params as $key => $value) {
                $placeholder = '{' . $key . '}';
                if (str_contains($url, $placeholder)) {
                    $url = str_replace($placeholder, rawurlencode((string)$value), $url);
                    unset($params[$key]);
                }
            }
            if ($params !== []) {
                $url .= '?' . http_build_query($params);
            }
            return rtrim(app()->basePath(), '/') . $url;
        }

        $routes = require __DIR__ . '/config/routes.php';
        $url = $routes[$name] ?? "/{$name}";
        
        foreach ($params as $key => $value) {
            $placeholder = '{' . $key . '}';
            if (str_contains($url, $placeholder)) {
                $url = str_replace($placeholder, rawurlencode((string)$value), $url);
                unset($params[$key]);
            }
        }
        if ($params !== []) {
            $url .= '?' . http_build_query($params);
        }
        return rtrim(app()->basePath(), '/') . $url;
    }
}

if (!function_exists('vite')) {
    function vite(string $entry): string
    {
        static $hotClientIncluded = false;
        $hotFile = APP_ROOT . '/public/hot';
        if (is_file($hotFile)) {
            $hotUrl = rtrim(trim((string)file_get_contents($hotFile)), '/');
            if (!filter_var($hotUrl, FILTER_VALIDATE_URL)) {
                throw new \RuntimeException('Invalid Vite development server URL in public/hot.');
            }

            $entryUrl = $hotUrl . '/' . ltrim($entry, '/');
            $client = '';
            if (!$hotClientIncluded) {
                $client = '<script type="module" src="' . htmlspecialchars($hotUrl . '/@vite/client', ENT_QUOTES, 'UTF-8') . '"></script>';
                $hotClientIncluded = true;
            }

            if (preg_match('/\.(?:css|scss|sass|less)$/', $entry)) {
                return $client . '<link rel="stylesheet" href="' . htmlspecialchars($entryUrl, ENT_QUOTES, 'UTF-8') . '">';
            }

            return $client . '<script type="module" src="' . htmlspecialchars($entryUrl, ENT_QUOTES, 'UTF-8') . '"></script>';
        }

        $manifestPath = APP_ROOT . '/public/assets/.vite/manifest.json';
        
        if (!file_exists($manifestPath)) {
            // Development - return dev server URL
            if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
                $entryUrl = 'http://localhost:5173/' . ltrim($entry, '/');
                return '<script type="module" src="http://localhost:5173/@vite/client"></script>'
                    . '<script type="module" src="' . htmlspecialchars($entryUrl, ENT_QUOTES, 'UTF-8') . '"></script>';
            }
            // Production fallback
            throw new \RuntimeException('Vite manifest not found. Run npm run build before serving production assets.');
        }
        
        $manifest = json_decode(file_get_contents($manifestPath), true);
        
        if (!isset($manifest[$entry])) {
            throw new \RuntimeException("Vite entry not found in manifest: {$entry}");
        }
        
        $entryInfo = $manifest[$entry];
        $url = asset($entryInfo['file']);
        
        // Include CSS imports
        $css = '';
        if (!empty($entryInfo['css'])) {
            foreach ($entryInfo['css'] as $cssFile) {
                $css .= '<link rel="stylesheet" href="' . htmlspecialchars(asset($cssFile), ENT_QUOTES, 'UTF-8') . '">';
            }
        }
        
        if (str_ends_with($entryInfo['file'], '.css')) {
            return $css . '<link rel="stylesheet" href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">';
        }

        return $css . '<script type="module" src="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"></script>';
    }
}

if (!function_exists('app')) {
    function app(string $abstract = null, array $parameters = []): mixed
    {
        $app = \Core\Application::getInstance();
        
        if ($abstract) {
            return $app->make($abstract, $parameters);
        }

        if (!function_exists('session')) {
            function session(): \Core\Session
            {
                return app('session');
            }
        }
        
        return $app;
    }
}