<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\View;
use Exception;

class PagesController
{
    public function showHomePage(): void
    {
        Auth::requireAuth();

        try {
            View::render('pages.home');
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    public function showAccountMenuPage(): void
    {
        Auth::requireAuth();

        try {
            View::render('pages.accountMenu');
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }
}
