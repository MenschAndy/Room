<?php
/**
 * Main Application Class
 */

namespace Core;

class Application
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        // Home
        $this->router->get('/', 'HomeController@index');

        // Rooms API
        $this->router->get('/api/rooms', 'RoomController@listPublic');
        $this->router->post('/api/rooms', 'RoomController@create');
        $this->router->get('/api/rooms/{id}', 'RoomController@show');
        $this->router->delete('/api/rooms/{id}', 'RoomController@delete');

        // Messages API
        $this->router->get('/api/rooms/{id}/messages', 'MessageController@listMessages');
        $this->router->post('/api/rooms/{id}/messages', 'MessageController@store');

        // Files API
        $this->router->post('/api/rooms/{id}/upload', 'FileController@upload');
        $this->router->get('/api/rooms/{id}/files', 'FileController@listFiles');

        // Auth API
        $this->router->post('/api/auth/register', 'AuthController@register');
        $this->router->post('/api/auth/login', 'AuthController@login');
        $this->router->post('/api/auth/logout', 'AuthController@logout');
    }

    public function run(): mixed
    {
        return $this->router->dispatch();
    }
}
