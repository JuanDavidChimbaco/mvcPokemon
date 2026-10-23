<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

$app = \Core\Application::getInstance();
$app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');

// Cargar rutas como en public/index.php
require_once dirname(__DIR__, 2) . '/app/config/routes.php';

if (is_dir(dirname(__DIR__, 2) . '/app/Modules')) {
    foreach (scandir(dirname(__DIR__, 2) . '/app/Modules') as $module) {
        if ($module !== '.' && $module !== '..' && is_dir(dirname(__DIR__, 2) . "/app/Modules/{$module}")) {
            $routesFile = dirname(__DIR__, 2) . "/app/Modules/{$module}/Routes.php";
            if (file_exists($routesFile)) {
                require_once $routesFile;
            }
        }
    }
}

$router = app('router');
$routes = $router->getRoutes();

echo "Registered routes:\n";
foreach ($routes as $method => $methodRoutes) {
    if ($method === 'ANY') continue;
    foreach ($methodRoutes as $uri => $route) {
        echo "$method $uri -> {$route->name} ({$route->action})\n";
    }
}