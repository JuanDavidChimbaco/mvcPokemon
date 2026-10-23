<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

// Debug: check what config returns
echo "config('database.default'): " . var_export(config('database.default'), true) . PHP_EOL;
echo "config('database.connections.mysql'): " . var_export(config('database.connections.mysql'), true) . PHP_EOL;

$migrator = new \Core\Migrator();
$migrator->status();