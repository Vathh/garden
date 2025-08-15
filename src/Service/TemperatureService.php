<?php

namespace App\Service;

use App\Core\Database;
use App\Model\TemperatureMeasurement;
use PDO;

class TemperatureService
{
    private String $url = "https://svr140.supla.org/direct/114/DbDsmcN3tNxUPvsT/read?format=json";
    public function __construct()
    {
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

        TemperatureMeasurement::addToDbWithActualDate(114, $temperature);
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

        return TemperatureMeasurement::fetchFromLastPeriod($interval);
    }
}
