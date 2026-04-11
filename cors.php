<?php
// cors.php

$allowedOrigins = [
    'http://localhost:4200',
    'https://tazerh-store.vercel.app',
    'https://tazerhstorephp.onrender.com'
];

// Get origin safely
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Allow only known origins
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
    header("Vary: Origin");
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

// Preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}