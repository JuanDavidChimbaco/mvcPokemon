<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

$app = \Core\Application::getInstance();
$app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');

echo "Testing view() function for shop/checkout/success...\n";
try {
    $html = view('shop.checkout.success', [
        'pedido' => (object)['id' => 123, 'total' => 150.50, 'created_at' => new DateTime(), 'getEstadoBadge' => fn() => 'success', 'direccion_envio' => 'Calle 123', 'notas' => 'Tarjeta', 'usuario' => (object)['nombre_completo' => 'Test User', 'telefono' => '3001234567']],
        'detalles' => []
    ]);
    echo "View rendered successfully! Length: " . strlen($html) . PHP_EOL;
    if (str_contains($html, 'Pedido Confirmado')) {
        echo "Content contains success message: YES\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}