<?php
namespace App\Core;

use eftec\bladeone\BladeOne;
use Jenssegers\Blade\Blade;

class View
{
    private static ?BladeOne $blade = null;

    /**
     * @throws \Exception
     */
    public static function render(string $template, array $data = []): void
    {
        if (self::$blade === null) {
            $views = dirname(__DIR__, 2) . '/resources/views';
            $cache = dirname(__DIR__, 2) . '/storage/cache';
            self::$blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);
        }

        echo self::$blade->run($template, $data);
    }
}
