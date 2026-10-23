<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

$app = \Core\Application::getInstance();
$app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');

echo "Testing view() function...\n";
try {
    $html = view('errors.500');
    echo "View rendered successfully! Length: " . strlen($html) . PHP_EOL;
    if (str_contains($html, '500')) {
        echo "Content contains 500 error code: YES\n";
    }
    if (str_contains($html, 'Error Interno')) {
        echo "Content contains error message: YES\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}