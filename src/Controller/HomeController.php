<?php

namespace App\Controller;

class HomeController
{
    public function loadHomePage(): void
    {

        session_start();
        if (!isset($_SESSION['user_id'])) {
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

        require_once __DIR__ . "/../View/home.php";
    }
}
