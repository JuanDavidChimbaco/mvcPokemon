<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

$app = \Core\Application::getInstance();
$app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');

// Check registered routes
$router = $app->router();
$routes = $router->getRoutes();

echo "Registered routes:\n";
foreach ($routes as $method => $methodRoutes) {
    if ($method === 'ANY') continue;
    foreach ($methodRoutes as $uri => $route) {
        echo "$method $uri -> {$route->name} ({$route->action})\n";
    }
}