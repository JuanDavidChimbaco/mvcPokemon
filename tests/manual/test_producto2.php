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

// Test query directly
$producto = \Shared\Models\Producto::active()->where('productos.id', '=', 1)->first();

echo "Producto: ";
var_dump($producto);

if ($producto) {
    echo "ID: " . $producto->id . PHP_EOL;
    echo "Nombre: " . $producto->nombrePro . PHP_EOL;
    echo "Categoria: " . $producto->categoria . PHP_EOL;
    echo "Categoria nombre: " . ($producto->categoria_nombre ?? 'N/A') . PHP_EOL;
}