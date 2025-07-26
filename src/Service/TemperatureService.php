<?php

namespace App\Service;

use App\Core\Database;
use DateTime;
use Exception;
use PDO;

class TemperatureService
{

    private PDO $conn;
    private String $url = "https://svr140.supla.org/direct/114/DbDsmcN3tNxUPvsT/read?format=json";
    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetch(): ?float
    {
        $json = file_get_contents($this->url);

        if ($json === false) {
            return null;
        }
        $data = json_decode($json, true);

        if (!$data['connected'] || !isset($data['temperature'])) {
            return null;
        }

        return (float) $data['temperature'];
    }

    public function fetchTemperatureAndSaveToDb(): void
    {
        $temperature = $this->fetch();

        $query = "
            INSERT INTO temperatures (sensor_id, value, created_at) VALUES (?, ?, ?);                                                            
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([114, $temperature, date('Y-m-d H:i:s')]);
    }

    public function getTemperatureDataForSelectedRange(string $range = '1h'): array
    {
        $interval = match ($range) {
            '6h' => '6 HOUR',
            '1d' => '1 DAY',
            '7d' => '7 DAY',
            '30d' => '30 DAY',
            default => '1 HOUR',
        };

        $query = "
            SELECT value, created_at
            FROM temperatures
            WHERE created_at >= NOW() - INTERVAL $interval
            ORDER BY created_at ASC
        ";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            try {
                $data[] = [
                    'time' => (new DateTime($row['created_at']))->format(DateTimeInterface::ATOM),
                    'temperature' => floatval($row['value'])
                ];
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        return $data;
    }
}
