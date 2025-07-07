<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\View;

class PagesController
{
    public function showHomePage(): void
    {
        Auth::requireAuth();

        View::render('pages.home');
    }

    public function showAccountMenuPage(): void
    {
        Auth::requireAuth();

        View::render('pages.accountMenu');
    }
}
