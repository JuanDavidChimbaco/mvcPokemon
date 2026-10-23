<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

$app = \Core\Application::getInstance();
$app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');

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

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/categorias';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';

$app = \Core\Application::getInstance();

try {
    $response = $app->handleRequest();
    echo "Status: " . $response->getStatusCode() . PHP_EOL;
    echo "Content length: " . strlen($response->getContent()) . PHP_EOL;
    echo "Content preview: " . substr($response->getContent(), 0, 500) . PHP_EOL;
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}