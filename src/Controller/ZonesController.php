<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\View;

class ZonesController
{
    public function showZonesMenuPage(): void
    {
        Auth::requireAuth();

        View::render('pages.zonesMenu');
    }

    public function showGreenHousePage(): void
    {
        Auth::requireAuth();

        View::render('pages.greenhouse');
    }

    public function showToolroomPage(): void
    {
        Auth::requireAuth();

        View::render('pages.toolroom');
    }
}
