<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

// Test config helper directly
echo "Testing config helper directly:\n";
echo "DB Host: " . config('database.connections.mysql.host') . PHP_EOL;
echo "DB Default: " . config('database.default') . PHP_EOL;