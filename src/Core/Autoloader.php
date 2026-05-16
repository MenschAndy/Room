<?php
/**
 * PSR-4 Autoloader
 */

class Autoloader
{
    public static function register(): void
    {
        spl_autoload_register([self::class, 'autoload']);
    }

    public static function autoload(string $class): void
    {
        $prefix = '';
        $baseDir = dirname(__DIR__);

        if (strpos($class, 'Core\\') === 0) {
            $path = $baseDir . '/Core/' . str_replace('\\', '/', substr($class, 5)) . '.php';
        } elseif (strpos($class, 'Models\\') === 0) {
            $path = $baseDir . '/Models/' . str_replace('\\', '/', substr($class, 7)) . '.php';
        } elseif (strpos($class, 'Controllers\\') === 0) {
            $path = $baseDir . '/Controllers/' . str_replace('\\', '/', substr($class, 12)) . '.php';
        } elseif (strpos($class, 'Helpers\\') === 0) {
            $path = $baseDir . '/Helpers/' . str_replace('\\', '/', substr($class, 8)) . '.php';
        } else {
            return;
        }

        if (file_exists($path)) {
            require $path;
        }
    }
}
