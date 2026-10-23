<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

$app = \Core\Application::getInstance();
$app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');

// Test controller instantiation
try {
    $ctrl = new \Modules\Shop\Controllers\ShopController();
    echo 'ShopController: OK' . PHP_EOL;
} catch (Exception $e) {
    echo 'ShopController ERROR: ' . $e->getMessage() . PHP_EOL;
}

try {
    $ctrl = new \Modules\Shop\Controllers\CartController();
    echo 'CartController: OK' . PHP_EOL;
} catch (Exception $e) {
    echo 'CartController ERROR: ' . $e->getMessage() . PHP_EOL;
}

try {
    $ctrl = new \Modules\Shop\Controllers\CheckoutController();
    echo 'CheckoutController: OK' . PHP_EOL;
} catch (Exception $e) {
    echo 'CheckoutController ERROR: ' . $e->getMessage() . PHP_EOL;
}

try {
    $ctrl = new \Modules\Auth\Controllers\AuthController();
    echo 'AuthController: OK' . PHP_EOL;
} catch (Exception $e) {
    echo 'AuthController ERROR: ' . $e->getMessage() . PHP_EOL;
}

try {
    $ctrl = new \Modules\Auth\Controllers\Api\AuthController();
    echo 'Api\AuthController: OK' . PHP_EOL;
} catch (Exception $e) {
    echo 'Api\AuthController ERROR: ' . $e->getMessage() . PHP_EOL;
}

try {
    $ctrl = new \Modules\Admin\Controllers\AuthController();
    echo 'Admin\AuthController: OK' . PHP_EOL;
} catch (Exception $e) {
    echo 'Admin\AuthController ERROR: ' . $e->getMessage() . PHP_EOL;
}

echo 'All controllers loaded successfully!' . PHP_EOL;