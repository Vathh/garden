<?php

function config(string $key)
{
    static $config = null;

    if ($config === null) {
        $config = require __DIR__ . '/../../config/env.php';
    }

    return $config[$key] ?? null;
}
