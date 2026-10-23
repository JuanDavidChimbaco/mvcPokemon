<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

$app = \Core\Application::getInstance();
$app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');
$db = \Core\Database::getInstance(config('database.connections.' . config('database.default')));

// Disable foreign key checks
$db->execute('SET FOREIGN_KEY_CHECKS = 0');

// Get all tables
$tables = $db->select('SHOW TABLES');
foreach ($tables as $table) {
    $tableName = array_values($table)[0];
    if ($tableName !== 'migrations') {
        $db->execute("DROP TABLE IF EXISTS `{$tableName}`");
        echo "Dropped: {$tableName}\n";
    }
}

// Re-enable foreign key checks
$db->execute('SET FOREIGN_KEY_CHECKS = 1');

echo "All tables dropped (except migrations)\n";