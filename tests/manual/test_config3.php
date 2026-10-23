<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

// Test config function multiple times
echo "Test 1: " . var_export(config('database.default'), true) . PHP_EOL;
echo "Test 2: " . var_export(config('database.default'), true) . PHP_EOL;
echo "Test 3: " . var_export(config('database.connections.mysql.host'), true) . PHP_EOL;

// Test direct require
$config = require dirname(__DIR__, 2) . '/app/config/app.php';
echo "Direct require: " . var_export($config['database']['default'], true) . PHP_EOL;