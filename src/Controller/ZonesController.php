<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;
use App\Model\TemperatureMeasurement;
use Exception;
use PDO;

class ZonesController
{
    public function __construct()
    {
    }

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

        $lastMeasurement = TemperatureMeasurement::fetchLastMeasurement();

        $internalTemperature = $lastMeasurement !== null ?
                                round($lastMeasurement->getTemperature(), 1) . "°C" : 'Brak danych';

        try {
            View::render('pages.greenhouse', [
                'internalTemperature' => $internalTemperature,
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
