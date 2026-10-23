<?php

/**
 * Front Controller - Punto de entrada único
 */

define('APP_START', microtime(true));
define('APP_ROOT', dirname(__DIR__));

// Cargar autoloader de Composer
require_once APP_ROOT . '/vendor/autoload.php';

// Bootstrap de la aplicación
require_once APP_ROOT . '/app/bootstrap.php';

// Crear y ejecutar aplicación
$app = \Core\Application::getInstance();

// Cargar configuración
$app->loadConfig(APP_ROOT . '/app/config/app.php');

// Cargar rutas
require_once APP_ROOT . '/app/config/routes.php';

// Registrar rutas de módulos
if (is_dir(APP_ROOT . '/app/Modules')) {
    foreach (scandir(APP_ROOT . '/app/Modules') as $module) {
        if ($module !== '.' && $module !== '..' && is_dir(APP_ROOT . "/app/Modules/{$module}")) {
            $routesFile = APP_ROOT . "/app/Modules/{$module}/Routes.php";
            if (file_exists($routesFile)) {
                require_once $routesFile;
            }
        }
    }
}

// Ejecutar
$app->run();