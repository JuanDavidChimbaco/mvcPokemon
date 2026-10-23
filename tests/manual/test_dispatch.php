<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

$app = \Core\Application::getInstance();
$app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');

$app->router()->get('/test', function() {
    return 'OK';
});

try {
    $response = $app->router()->dispatch('GET', '/test');
    echo "Response type: " . gettype($response) . PHP_EOL;
    echo "Response: " . $response . PHP_EOL;
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}