<?php
namespace App\Core;

use Jenssegers\Blade\Blade;

class View
{
    private static ?Blade $blade = null;

    public static function render(string $template, array $data = []): void
    {
        if (self::$blade === null) {
            $views = dirname(__DIR__, 2) . '/resources/views';
            $cache = dirname(__DIR__, 2) . '/storage/cache';
            self::$blade = new Blade($views, $cache);
        }

        echo self::$blade->make($template, $data)->render();
    }
}
