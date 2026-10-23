<?php

namespace Core;

/**
 * Router simple con soporte para métodos HTTP, parámetros, middleware y grupos
 */
class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
        'OPTIONS' => [],
        'ANY' => [],
    ];
    
    private array $middleware = [];
    private array $groupMiddleware = [];
    private string $currentGroupPrefix = '';
    private string $currentGroupName = '';
    private array $namedRoutes = [];
    
    // ===== Métodos HTTP =====
    
    public function get(string $uri, callable|array|string $action, array $middleware = []): Route
    {
        return $this->addRoute('GET', $uri, $action, $middleware);
    }
    
    public function post(string $uri, callable|array|string $action, array $middleware = []): Route
    {
        return $this->addRoute('POST', $uri, $action, $middleware);
    }
    
    public function put(string $uri, callable|array|string $action, array $middleware = []): Route
    {
        return $this->addRoute('PUT', $uri, $action, $middleware);
    }
    
    public function patch(string $uri, callable|array|string $action, array $middleware = []): Route
    {
        return $this->addRoute('PATCH', $uri, $action, $middleware);
    }
    
    public function delete(string $uri, callable|array|string $action, array $middleware = []): Route
    {
        return $this->addRoute('DELETE', $uri, $action, $middleware);
    }
    
    public function any(string $uri, callable|array|string $action, array $middleware = []): Route
    {
        return $this->addRoute('ANY', $uri, $action, $middleware);
    }
    
    public function match(array $methods, string $uri, callable|array|string $action, array $middleware = []): Route
    {
        $route = null;
        foreach ($methods as $method) {
            $route = $this->addRoute(strtoupper($method), $uri, $action, $middleware);
        }
        return $route;
    }
    
    // ===== Grupos =====
    
    public function group(array $attributes, callable $callback): void
    {
        $prefix = $attributes['prefix'] ?? '';
        $name = $attributes['as'] ?? '';
        $middleware = $attributes['middleware'] ?? [];
        
        $previousPrefix = $this->currentGroupPrefix;
        $previousName = $this->currentGroupName;
        $previousMiddleware = $this->groupMiddleware;
        
        $this->currentGroupPrefix = $previousPrefix . $prefix;
        $this->currentGroupName = $previousName . $name;
        $this->groupMiddleware = array_merge($previousMiddleware, $middleware);
        
        $callback($this);
        
        $this->currentGroupPrefix = $previousPrefix;
        $this->currentGroupName = $previousName;
        $this->groupMiddleware = $previousMiddleware;
    }
    
    // ===== Resource routes (RESTful) =====
    
    public function resource(string $name, string $controller, array $options = []): void
    {
        $only = $options['only'] ?? ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
        $except = $options['except'] ?? [];
        $methods = array_diff($only, $except);
        
        $baseUri = '/' . $name;
        $baseName = $this->currentGroupName . '.' . $name;
        
        if (in_array('index', $methods)) {
            $this->get($baseUri, [$controller, 'index'])->name("{$baseName}.index");
        }
        if (in_array('create', $methods)) {
            $this->get($baseUri . '/crear', [$controller, 'create'])->name("{$baseName}.create");
        }
        if (in_array('store', $methods)) {
            $this->post($baseUri, [$controller, 'store'])->name("{$baseName}.store");
        }
        if (in_array('show', $methods)) {
            $this->get($baseUri . '/{id}', [$controller, 'show'])->name("{$baseName}.show");
        }
        if (in_array('edit', $methods)) {
            $this->get($baseUri . '/{id}/editar', [$controller, 'edit'])->name("{$baseName}.edit");
        }
        if (in_array('update', $methods)) {
            $this->match(['PUT', 'PATCH'], $baseUri . '/{id}', [$controller, 'update'])->name("{$baseName}.update");
        }
        if (in_array('destroy', $methods)) {
            $this->delete($baseUri . '/{id}', [$controller, 'destroy'])->name("{$baseName}.destroy");
        }
    }
    
    // ===== API Resource =====
    
    public function apiResource(string $name, string $controller, array $options = []): void
    {
        $only = $options['only'] ?? ['index', 'store', 'show', 'update', 'destroy'];
        $except = $options['except'] ?? [];
        $methods = array_diff($only, $except);
        
        $baseUri = '/api/' . $name;
        $baseName = $this->currentGroupName . '.api.' . $name;
        
        if (in_array('index', $methods)) {
            $this->get($baseUri, [$controller, 'index'])->name("{$baseName}.index");
        }
        if (in_array('store', $methods)) {
            $this->post($baseUri, [$controller, 'store'])->name("{$baseName}.store");
        }
        if (in_array('show', $methods)) {
            $this->get($baseUri . '/{id}', [$controller, 'show'])->name("{$baseName}.show");
        }
        if (in_array('update', $methods)) {
            $this->match(['PUT', 'PATCH'], $baseUri . '/{id}', [$controller, 'update'])->name("{$baseName}.update");
        }
        if (in_array('destroy', $methods)) {
            $this->delete($baseUri . '/{id}', [$controller, 'destroy'])->name("{$baseName}.destroy");
        }
    }
    
    // ===== Internos =====
    
    private function addRoute(string $method, string $uri, callable|array|string $action, array $middleware): Route
    {
        $uri = $this->currentGroupPrefix . $uri;
        $name = $this->currentGroupName;
        $middleware = array_merge($this->groupMiddleware, $middleware);
        
        $route = new Route($method, $uri, $action, $middleware, $name);
        $this->routes[$method][$uri] = $route;
        
        if ($method !== 'ANY') {
            $this->routes['ANY'][$uri] = $route;
        }
        
        return $route;
    }
    
    // ===== Dispatch =====
    
    public function dispatch(string $method, string $uri): mixed
    {
        // Normalizar URI
        $uri = $this->normalizeUri($uri);
        $method = strtoupper($method);
        
        // Buscar ruta exacta
        $route = $this->findRoute($method, $uri);
        
        if (!$route) {
            // Buscar en ANY
            $route = $this->findRoute('ANY', $uri);
        }
        
        if (!$route) {
            throw new \RuntimeException("Route not found: {$method} {$uri}", 404);
        }
        
        // Ejecutar middleware antes
        foreach ($route->middleware as $mw) {
            $result = $this->executeMiddleware($mw);
            if ($result !== null) {
                return $result; // Middleware retornó respuesta (ej: redirect)
            }
        }
        
        // Ejecutar controlador
        return $this->executeRoute($route);
    }
    
    private function findRoute(string $method, string $uri): ?Route
    {
        // Exact match primero
        if (isset($this->routes[$method][$uri])) {
            return $this->routes[$method][$uri];
        }
        
        // Buscar con parámetros
        foreach ($this->routes[$method] as $pattern => $route) {
            if ($this->matchRoute($pattern, $uri)) {
                return $route;
            }
        }
        
        return null;
    }
    
    private function matchRoute(string $pattern, string $uri): bool
    {
        // Convertir {param} a regex
        $regex = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        
        if (preg_match($regex, $uri, $matches)) {
            // Extraer parámetros nombrados
            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            // Guardar params en el request global o route
            $_SERVER['ROUTE_PARAMS'] = $params;
            return true;
        }
        
        return false;
    }
    
    private function executeRoute(Route $route): mixed
    {
        $action = $route->action;
        $params = $_SERVER['ROUTE_PARAMS'] ?? [];
        
        if (is_callable($action)) {
            return $action(...array_values($params));
        }
        
        if (is_array($action) && count($action) === 2) {
            [$controllerClass, $method] = $action;
            
            // Resolver controlador desde container o instanciar
            $controller = $this->resolveController($controllerClass);
            
            if (!method_exists($controller, $method)) {
                throw new \RuntimeException("Method {$method} not found in {$controllerClass}");
            }
            
            return $controller->$method(...array_values($params));
        }
        
        if (is_string($action)) {
            // Formato "Controller@method"
            [$controllerClass, $method] = explode('@', $action);
            $controller = $this->resolveController($controllerClass);
            return $controller->$method(...array_values($params));
        }
        
        throw new \RuntimeException('Invalid route action');
    }
    
    private function resolveController(string $class): object
    {
        if (class_exists($class)) {
            return new $class();
        }
        
        // Intentar con namespace Modules
        $fullClass = "Modules\\{$class}";
        if (class_exists($fullClass)) {
            return new $fullClass();
        }
        
        // Intentar con patrón Modules\{Module}\Controllers\{Controller}
        // Asumimos que $class es solo el nombre del controlador (ej: ShopController)
        foreach (['Shop', 'Admin', 'Auth', 'Api'] as $module) {
            $fullClass = "Modules\\{$module}\\Controllers\\{$class}";
            if (class_exists($fullClass)) {
                return new $fullClass();
            }
        }
        
        throw new \RuntimeException("Controller not found: {$class}");
    }
    
    private function executeMiddleware(string $middleware): mixed
    {
        $instance = app()->middleware($middleware);
        if ($instance && method_exists($instance, 'handle')) {
            return $instance->handle();
        }

        if (class_exists($middleware)) {
            $instance = new $middleware();
            if (method_exists($instance, 'handle')) {
                return $instance->handle();
            }
        }
        
        if (is_callable($middleware)) {
            return $middleware();
        }
        
        return null;
    }
    
    private function normalizeUri(string $uri): string
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?? $uri;
        $uri = '/' . trim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }
    
    // ===== Helpers =====
    
    public function getRoutes(): array
    {
        return $this->routes;
    }
    
    public function registerNamedRoute(string $name, Route $route): void
    {
        $this->namedRoutes[$name] = $route;
    }
    
    public function hasRoute(string $name): bool
    {
        return isset($this->namedRoutes[$name]);
    }
    
    public function getNamedRoute(string $name): ?Route
    {
        return $this->namedRoutes[$name] ?? null;
    }
    
    public function url(string $name, array $params = []): string
    {
        $route = $this->getNamedRoute($name);
        if (!$route) return "/{$name}";
        
        $url = $route->uri;
        foreach ($params as $key => $value) {
            $url = str_replace("{{$key}}", $value, $url);
        }
        return $url;
    }
}

/**
 * Route object
 */
class Route
{
    public function __construct(
        public string $method,
        public string $uri,
        public mixed $action,
        public array $middleware = [],
        public string $name = ''
    ) {}
    
    public function name(string $name): self
    {
        $this->name = $name;
        // Registrar en router global
        app('router')->registerNamedRoute($name, $this);
        return $this;
    }
    
    public function middleware(array|string $middleware): self
    {
        $this->middleware = array_merge($this->middleware, (array)$middleware);
        return $this;
    }
    
    public function where(string $param, string $regex): self
    {
        // Para futuras validaciones de parámetros
        return $this;
    }
}