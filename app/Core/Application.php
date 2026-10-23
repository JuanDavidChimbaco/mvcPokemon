<?php

namespace Core;

/**
 * Application - Front Controller y Service Container simple
 */
class Application
{
    private static ?self $instance = null;
    
    private array $bindings = [];
    private array $instances = [];
    private array $config = [];
    private ?Router $router = null;
    private ?Request $request = null;
    private ?Response $response = null;
    private ?Session $session = null;
    private ?Database $database = null;
    private ?Cache $cache = null;
    private array $middleware = [];
    
    private function __construct()
    {
        $this->registerCoreBindings();
        $this->registerMiddleware();
    }
    
    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }
    
    public static function setInstance(self $instance): void
    {
        self::$instance = $instance;
    }
    
    private function registerCoreBindings(): void
    {
        $this->bind('app', fn() => $this);
        $this->bind('config', fn() => $this->config);
        $this->bind('router', fn() => $this->router());
        $this->bind('request', fn() => $this->request());
        $this->bind('response', fn() => $this->response());
        $this->bind('session', fn() => $this->session());
        $this->bind('db', fn() => $this->database());
        $this->bind('cache', fn() => $this->cache());
    }
    
    private function registerMiddleware(): void
    {
        $this->middleware = [
            'auth' => \Core\AuthMiddleware::class,
            'admin' => \Core\AdminMiddleware::class,
            'session' => \Core\SessionMiddleware::class,
            'csrf' => \Core\CsrfMiddleware::class,
            'throttle' => fn($max = 60, $window = 60) => new \Core\RateLimitMiddleware($max, $window),
        ];
    }
    
    // ===== Service Container =====
    
    public function bind(string $abstract, callable|string $concrete, bool $shared = false): void
    {
        $this->bindings[$abstract] = ['concrete' => $concrete, 'shared' => $shared];
        unset($this->instances[$abstract]);
    }
    
    public function singleton(string $abstract, callable|string $concrete): void
    {
        $this->bind($abstract, $concrete, true);
    }
    
    public function instance(string $abstract, mixed $instance): void
    {
        $this->instances[$abstract] = $instance;
    }
    
    public function make(string $abstract, array $parameters = []): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        
        if (!isset($this->bindings[$abstract])) {
            // Intentar autowiring
            return $this->resolve($abstract, $parameters);
        }
        
        $binding = $this->bindings[$abstract];
        $concrete = $binding['concrete'];
        
        $instance = is_callable($concrete) 
            ? $concrete($this, $parameters) 
            : $this->resolve($concrete, $parameters);
        
        if ($binding['shared']) {
            $this->instances[$abstract] = $instance;
        }
        
        return $instance;
    }
    
    public function resolve(string $class, array $parameters = []): mixed
    {
        $reflection = new \ReflectionClass($class);
        
        if (!$reflection->isInstantiable()) {
            throw new \RuntimeException("Class {$class} is not instantiable");
        }
        
        $constructor = $reflection->getConstructor();
        
        if (!$constructor) {
            return new $class();
        }
        
        $dependencies = [];
        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();
            
            if ($type && !$type->isBuiltin()) {
                $typeName = $type->getName();
                $dependencies[] = $this->make($typeName);
            } elseif (isset($parameters[$name])) {
                $dependencies[] = $parameters[$name];
            } elseif ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
            } else {
                throw new \RuntimeException("Cannot resolve parameter {$name} for {$class}");
            }
        }
        
        return $reflection->newInstanceArgs($dependencies);
    }
    
    public function __call(string $method, array $args): mixed
    {
        if (isset($this->bindings[$method]) || isset($this->instances[$method])) {
            return $this->make($method);
        }
        
        throw new \BadMethodCallException("Service {$method} not found");
    }
    
    public function __get(string $key): mixed
    {
        return $this->make($key);
    }
    
    public function __set(string $key, mixed $value): void
    {
        $this->instance($key, $value);
    }
    
    public function __isset(string $key): bool
    {
        return isset($this->bindings[$key]) || isset($this->instances[$key]);
    }
    
    // ===== Core Services =====
    
    public function router(): Router
    {
        return $this->router ??= new Router();
    }
    
    public function request(): Request
    {
        return $this->request ??= new Request();
    }
    
    public function response(): Response
    {
        return $this->response ??= new Response();
    }
    
    public function session(): Session
    {
        if (!$this->session) {
            $this->session = new Session(config('session') ?? []);
            $this->session->start();
        }
        return $this->session;
    }
    
    public function database(): Database
    {
        return $this->database ??= Database::getInstance(config('database.connections.' . config('database.default')));
    }
    
    public function cache(): Cache
    {
        return $this->cache ??= new Cache(config('cache.path'), config('cache.ttl', 3600));
    }
    
    public function middleware(string $name): mixed
    {
        if (isset($this->middleware[$name])) {
            $mw = $this->middleware[$name];
            return is_callable($mw) ? $mw() : new $mw();
        }
        return null;
    }
    
    public function config(): array
    {
        return $this->config;
    }
    
    public function loadConfig(string $file): void
    {
        $this->config = array_merge($this->config, require $file);
    }
    
    // ===== Routing =====
    
    public function handleRequest(): Response
    {
        try {
            $method = $this->request()->method();
            $uri = $this->request()->path();
            $basePath = $this->basePath();
            if ($basePath !== '' && str_starts_with(strtolower($uri), strtolower($basePath . '/'))) {
                $uri = substr($uri, strlen($basePath));
            } elseif ($basePath !== '' && strtolower($uri) === strtolower($basePath)) {
                $uri = '/';
            }
            
            // Ejecutar middleware global (session, csrf para POST)
            if ($method !== 'GET') {
                $csrfResult = $this->middleware('csrf')?->handle();
                if ($csrfResult instanceof Response) return $csrfResult;
            }
            
            $response = $this->router()->dispatch($method, $uri);
            
            if ($response instanceof Response) {
                return $response;
            }
            
            // Si el controlador devuelve array/string, envolver en Response
            return Response::make($response);
            
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }
    
    private function handleException(\Throwable $e): Response
    {
        $statusCode = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;

        if ($e instanceof ValidationException) {
            if ($this->request()->isJson() || $this->request()->isAjax()) {
                return Response::json([
                    'error' => 'Error de validación',
                    'errors' => $e->errors,
                ], 422);
            }

            $messages = array_merge(...array_values($e->errors));
            $this->session()->flash('error', implode('. ', $messages));
            $this->session()->flashInput($this->request()->except([
                'password',
                'password_confirmation',
                '_token',
            ]));

            return Response::back();
        }
        
        // Log error
        error_log(sprintf(
            "[%s] %s in %s:%d\n%s\n",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        ));
        
        if ($this->request()->isJson() || $this->request()->isAjax()) {
            return Response::json([
                'error' => $e->getMessage(),
                'trace' => config('debug') ? $e->getTraceAsString() : null,
            ], $statusCode);
        }
        
        if (config('debug')) {
            return Response::make("<pre>{$e->getMessage()}\n\n{$e->getTraceAsString()}</pre>", $statusCode);
        }
        
        return Response::view('errors.' . $statusCode, ['message' => $e->getMessage()], $statusCode);
    }
    
    public function run(): void
    {
        $response = $this->handleRequest();
        $response->send();
    }

    public function basePath(): string
    {
        $documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
        $appRoot = realpath(APP_ROOT);
        if ($documentRoot === false || $appRoot === false) {
            return '';
        }

        $documentRoot = rtrim(str_replace('\\', '/', $documentRoot), '/');
        $appRoot = rtrim(str_replace('\\', '/', $appRoot), '/');
        $prefix = $documentRoot . '/';
        if (stripos($appRoot, $prefix) !== 0) {
            return '';
        }

        return '/' . trim(substr($appRoot, strlen($prefix)), '/');
    }
    
    // ===== Helpers =====
    
    public function version(): string
    {
        return '1.0.0';
    }
    
    public function environment(): string
    {
        return config('env', 'production');
    }
    
    public function isDevelopment(): bool
    {
        return $this->environment() === 'development';
    }
    
    public function isProduction(): bool
    {
        return $this->environment() === 'production';
    }
}