<?php
/**
 * User Model
 */

namespace Models;

use Core\Database;
use Helpers\hashPassword;
use Helpers\verifyPassword;

class User
{
    public static function create(array $data): int|false
    {
        if (self::exists($data['email'])) {
            return false;
        }

        try {
            Database::execute(
                'INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)',
                [
                    $data['username'],
                    $data['email'],
                    hashPassword($data['password']),
                ]
            );
            return (int)Database::lastInsertId();
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getByEmail(string $email): array|bool
    {
        return Database::queryOne(
            'SELECT * FROM users WHERE email = ?',
            [$email]
        );
    }

    public static function getById(int $id): array|bool
    {
        return Database::queryOne(
            'SELECT id, username, email, created_at FROM users WHERE id = ?',
            [$id]
        );
    }

    public static function exists(string $email): bool
    {
        $result = Database::queryOne(
            'SELECT 1 FROM users WHERE email = ?',
            [$email]
        );
        return (bool)$result;
    }

    public static function authenticate(string $email, string $password): array|false
    {
        $user = self::getByEmail($email);
        if (!$user || !verifyPassword($password, $user['password_hash'])) {
            return false;
        }
        unset($user['password_hash']);
        return $user;
    }
}
