<?php
/**
 * Auth Controller
 */

namespace Controllers;

use Models\User;
use Helpers\response;
use Helpers\sanitize;

class AuthController
{
    public function register(): never
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password']) || empty($data['username'])) {
            response(['error' => 'Missing required fields'], 400);
        }

        $userId = User::create([
            'username' => sanitize($data['username']),
            'email' => filter_var($data['email'], FILTER_VALIDATE_EMAIL),
            'password' => $data['password'],
        ]);

        if (!$userId) {
            response(['error' => 'Registration failed'], 400);
        }

        response(['success' => true, 'user_id' => $userId, 'message' => 'Registration successful']);
    }

    public function login(): never
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            response(['error' => 'Email and password required'], 400);
        }

        $user = User::authenticate($data['email'], $data['password']);
        if (!$user) {
            response(['error' => 'Invalid credentials'], 401);
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        response(['success' => true, 'user' => $user]);
    }

    public function logout(): never
    {
        session_destroy();
        response(['success' => true, 'message' => 'Logged out successfully']);
    }
}
