<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\View;
use App\Service\TemperatureFetcher;
use Exception;

class ZonesController
{
    public function showZonesMenuPage(): void
    {
        Auth::requireAuth();

        try {
            View::render('pages.zonesMenu');
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    public function showGreenHousePage(): void
    {
        Auth::requireAuth();

        $temperatureFetcher = new TemperatureFetcher();

        $internalTemperature = $temperatureFetcher->fetch();

        try {
            View::render('pages.greenhouse', [
                'internalTemperature' => $internalTemperature
            ]);
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    public function showToolroomPage(): void
    {
        Auth::requireAuth();

        try {
            View::render('pages.toolroom');
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }
}
