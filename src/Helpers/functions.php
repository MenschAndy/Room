<?php
/**
 * Helper Functions
 */

namespace Helpers;

function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;
    
    if ($value === null) {
        return $default;
    }
    
    return match (strtolower($value)) {
        'true' => true,
        'false' => false,
        'null' => null,
        default => $value,
    };
}

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) {
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, ' "');
            
            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }
    }
}

function generateToken(int $length = 32): string
{
    return bin2hex(random_bytes($length / 2));
}

function hashPassword(string $password): string
{
    return password_hash($password, PASSWORD_ARGON2ID, ['memory_cost' => 65536, 'time_cost' => 4, 'threads' => 3]);
}

function verifyPassword(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

function sanitize(string $input): string
{
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function response(array $data, int $statusCode = 200): never
{
    header('Content-Type: application/json', true, $statusCode);
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}
