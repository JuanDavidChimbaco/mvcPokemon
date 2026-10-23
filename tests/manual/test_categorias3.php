<?php

define('APP_START', microtime(true));
define('APP_ROOT', dirname(__DIR__, 2));

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
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 2) . '/public';

$app = \Core\Application::getInstance();

// Debug: check what the query returns
$categorias = \Shared\Models\Categoria::ordered()->get();

echo "Categorias count: " . count($categorias) . PHP_EOL;
foreach ($categorias as $cat) {
    echo "ID: " . $cat->id . " | Nombre: " . $cat->nombreCat . " | Estado: " . $cat->estado . PHP_EOL;
}