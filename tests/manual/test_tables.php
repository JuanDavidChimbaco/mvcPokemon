<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

$app = \Core\Application::getInstance();
$app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');
$db = \Core\Database::getInstance(config('database.connections.' . config('database.default')));
$tables = $db->select('SHOW TABLES');
print_r($tables);