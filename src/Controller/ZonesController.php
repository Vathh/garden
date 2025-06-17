<?php

namespace App\Controller;

use App\Core\Auth;

class ZonesController
{
    public function showZonesMenuPage(): void
    {
        Auth::requireAuth();

        require_once __DIR__ . "/../View/zonesMenu.php";
    }

    public function showGreenHousePage(): void
    {
        Auth::requireAuth();

        require_once __DIR__ . "/../View/greenHouse.php";
    }

    public function showToolroomPage(): void
    {
        Auth::requireAuth();

        require_once __DIR__ . "/../View/toolroom.php";
    }
}
