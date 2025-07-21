<?php

namespace App\Service;

use App\Core\Database;
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
}
