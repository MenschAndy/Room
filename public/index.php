<?php
/**
 * Application Entry Point
 * PHP 8+ Rooms Application
 */

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

// Define base path
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Autoloader
require_once BASE_PATH . '/src/Core/Autoloader.php';
Autoloader::register();

// Load environment variables
require_once BASE_PATH . '/src/Helpers/functions.php';
loadEnv(BASE_PATH . '/.env');

// Initialize application
try {
    $app = new \Core\Application();
    $app->run();
} catch (Exception $e) {
    http_response_code(500);
    if (env('APP_DEBUG', false)) {
        echo json_encode(['error' => $e->getMessage()]);
    } else {
        echo json_encode(['error' => 'Internal Server Error']);
    }
}
