<?php
/**
 * Home Controller
 */

namespace Controllers;

class HomeController
{
    public function index(): string
    {
        // Render landing page
        return file_get_contents(BASE_PATH . '/src/Views/home.html');
    }
}
