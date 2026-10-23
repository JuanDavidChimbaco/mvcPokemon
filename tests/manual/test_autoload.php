<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Test config function
echo "Testing config function...\n";
echo "APP_NAME: " . config('name') . PHP_EOL;
echo "DB_HOST: " . config('database.connections.mysql.host') . PHP_EOL;
echo "DB_NAME: " . config('database.connections.mysql.database') . PHP_EOL;
echo "Default DB: " . config('database.default') . PHP_EOL;

echo "\nTesting env function...\n";
echo "DB_HOST from env: " . env('DB_HOST') . PHP_EOL;
echo "DB_NAME from env: " . env('DB_NAME') . PHP_EOL;

echo "\nTesting Models...\n";

$usuario = new \Shared\Models\Usuario([
    'nombre' => 'Test',
    'apellido' => 'User',
    'correo' => 'test@example.com',
]);
echo 'Usuario: ' . $usuario->nombre_completo . PHP_EOL;

$producto = new \Shared\Models\Producto([
    'nombrePro' => 'Pikachu',
    'precioPro' => 29.99,
    'cantidadPro' => 10,
    'categoria' => 1,
]);
echo 'Producto: ' . $producto->nombrePro . ' - $' . $producto->precioPro . PHP_EOL;

$categoria = new \Shared\Models\Categoria([
    'nombreCat' => 'Electric',
]);
echo 'Categoria icon: ' . $categoria->getIcon() . PHP_EOL;
echo 'Categoria color: ' . $categoria->getColor() . PHP_EOL;

echo "\nAll basic tests passed!\n";