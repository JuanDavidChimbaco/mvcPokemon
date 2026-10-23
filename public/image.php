<?php

$filename = $_GET['file'] ?? '';
if (!is_string($filename) || !preg_match('/\A[A-Za-z0-9_.-]+\.(?:png|jpe?g|svg|webp)\z/i', $filename)) {
    http_response_code(404);
    exit;
}

$path = dirname(__DIR__) . '/legacy/views/PokeApi/assets_Front/img/' . $filename;
if (!is_file($path)) {
    $path = dirname(__DIR__) . '/legacy/views/PokeApi/assets_Front/img/placeholder.svg';
}

$mimeType = mime_content_type($path);
if (!is_string($mimeType) || !str_starts_with($mimeType, 'image/')) {
    http_response_code(415);
    exit;
}

header('Content-Type: ' . $mimeType);
header('Content-Length: ' . filesize($path));
header('Cache-Control: public, max-age=86400');
header('X-Content-Type-Options: nosniff');
readfile($path);
