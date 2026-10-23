<?php

return [
    'name' => env('APP_NAME', 'PokéTienda'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'America/Mexico_City'),
    'locale' => env('APP_LOCALE', 'es'),
    
    'session' => [
        'driver' => env('SESSION_DRIVER', 'file'),
        'lifetime' => env('SESSION_LIFETIME', 120),
        'encrypt' => env('SESSION_ENCRYPT', false),
        'files' => __DIR__ . '/../../storage/sessions',
        'cookie' => env('SESSION_COOKIE', 'pokemon_session'),
        'path' => '/',
        'domain' => env('SESSION_DOMAIN', null),
        'secure' => env('SESSION_SECURE', false),
        'httponly' => true,
        'samesite' => 'lax',
    ],
    
    'database' => [
        'default' => env('DB_CONNECTION', 'mysql'),
        'connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_NAME', 'pokemon33'),
                'username' => env('DB_USER', 'root'),
                'password' => env('DB_PASS', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => 'InnoDB',
                'options' => [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                ],
            ],
        ],
    ],
    
    'pagination' => [
        'per_page' => env('PAGINATION_PER_PAGE', 12),
    ],
    
    'cache' => [
        'driver' => env('CACHE_DRIVER', 'file'),
        'path' => __DIR__ . '/../../storage/cache',
    ],
    
    'logging' => [
        'channel' => env('LOG_CHANNEL', 'single'),
        'path' => __DIR__ . '/../../storage/logs/app.log',
        'level' => env('LOG_LEVEL', 'debug'),
    ],
];