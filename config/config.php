<?php
/**
 * Application Configuration
 * PHP 8+ Compatible
 */

use function Helpers\env;

return [
    // Database
    'database' => [
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', 3306),
        'user' => env('DB_USER', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'name' => env('DB_NAME', 'rooms_db'),
        'charset' => 'utf8mb4',
    ],
    
    // Application
    'app' => [
        'name' => 'BoltShare Rooms',
        'url' => env('APP_URL', 'http://localhost'),
        'env' => env('APP_ENV', 'production'),
        'debug' => env('APP_DEBUG', false),
        'timezone' => 'UTC',
    ],
    
    // Upload
    'upload' => [
        'max_size' => env('MAX_UPLOAD_SIZE', 104857600), // 100MB
        'allowed_extensions' => explode(',', env('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,mp4,webm,pdf,zip')),
        'upload_dir' => __DIR__ . '/../public/uploads/',
    ],
    
    // Room
    'room' => [
        'expiry_time' => env('ROOM_EXPIRY_TIME', 3600), // 1 hour
        'max_items' => env('MAX_ROOM_ITEMS', 500),
        'auto_cleanup_enabled' => true,
    ],
    
    // Security
    'security' => [
        'session_timeout' => env('SESSION_TIMEOUT', 1800),
        'csrf_token_length' => env('CSRF_TOKEN_LENGTH', 32),
    ],
];
