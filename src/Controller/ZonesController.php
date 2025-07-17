<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;
use Exception;
use PDO;

class ZonesController
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
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

        $stmt = $this->conn->prepare("SELECT value FROM temperatures ORDER BY created_at DESC LIMIT 1");
        $stmt->execute();

        $internalTemperature = $stmt->fetchColumn();

        try {
            View::render('pages.greenhouse', [
                'internalTemperature' => round($internalTemperature, 1),
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
