<?php

namespace App\Controller;

use App\Core\Auth;

class PagesController
{
    public function showHomePage(): void
    {
        Auth::requireAuth();

        require_once __DIR__ . "/../View/home.php";
    }

    public function showAccountMenuPage(): void
    {
        Auth::requireAuth();

        require_once __DIR__ . "/../View/accountMenu.php";
    }
}
