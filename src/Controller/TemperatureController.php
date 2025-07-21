<?php

namespace App\Controller;

use App\Core\Database;
use PDO;

class TemperatureController
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getTemperatureChartData()
    {
        $range = $_GET["range"] ?? '1h';

        $interval = match ($range) {
            '6h' => '6 HOUR',
            '1d' => '1 DAY',
            '7d' => '7 DAY',
            default => '1 HOUR',
        };

        $stmt = $this->conn->prepare("
            SELECT value, created_at
            FROM temperatures
            WHERE created_at >= NOW() - INTERVAL $interval
            ORDER BY created_at ASC
        ");

        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = [
                'time' => date('H:i', strtotime($row['created_at'])),
                'temperature' => floatval($row['value'])
            ];
        }

        header('Content-type: application/json');
        echo json_encode($data);
        exit;
    }
}
