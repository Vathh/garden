<?php

namespace App\Core;

class Auth
{
    public static function requireAuth(): void
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        if (!(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] < 600))) {
            session_unset();
            session_destroy();
            header("Location: /login?msg=inactive");
            exit;
        }

        $_SESSION['last_activity'] = time();
    }

    public static function userHasPermission(string $permission): bool
    {
        return isset($_SESSION['user'])
            && $_SESSION['user']->hasPermission($permission);
    }

    public static function startSessionIfItsNot(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
