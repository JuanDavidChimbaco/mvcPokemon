<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

$app = \Core\Application::getInstance();
$app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');

echo 'DB Host: ' . config('database.connections.mysql.host') . PHP_EOL;
echo 'DB Name: ' . config('database.connections.mysql.database') . PHP_EOL;
echo 'DB User: ' . config('database.connections.mysql.username') . PHP_EOL;

try {
    $db = \Core\Database::getInstance(config('database.connections.' . config('database.default')));
    echo "Database connection OK\n";
    $pdo = $db->getPdo();
    echo "PDO: " . get_class($pdo) . PHP_EOL;
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}