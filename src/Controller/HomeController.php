<?php

namespace App\Controller;

use App\Core\Auth;

class HomeController
{
    public function loadHomePage(): void
    {
        Auth::requireAuth();

        require_once __DIR__ . "/../View/home.php";
    }
}
