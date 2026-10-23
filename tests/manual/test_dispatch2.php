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

// Simulate a web request to /
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';

$app = \Core\Application::getInstance();

try {
    $response = $app->run();
    echo "Response sent successfully\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}