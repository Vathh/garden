<?php

namespace App\Service;

use App\Model\HumidityMeasurement;

class HumidityService
{
    private String $url = 'https://link_do_czujnika/';
    public static function fetchAll(): array
    {
        // FEJKOWE DANE ZEBY FUNKCJA COS ZWRACALA

        $sensorIds = [1, 2, 3];  // id wszystkich sensorow trzymane bylyby gdzies w bazie lub osobnym pliku
        $humidityMeasurements = [];

        foreach ($sensorIds as $sensorId) {
            $humidityMeasurements[] = (new HumidityMeasurement())->setDateTime(date('Y-m-d H:i:s'))
                                                                    ->setHumidity(10*$sensorId)
                                                                    ->setSensorId($sensorId);
        }

            // PRZYKLADOWY KOD POBIERAJACY DANE Z CZUJNIKOW

//        $sensorIds = [1, 2, 3];
//        $humidityMeasurements = [];
//        foreach ($sensorIds as $sensorId) {
//            $humidityMeasurements[]  = $this->fetchBySensorId($sensorId) ?: null;
//        }

        return $humidityMeasurements;
    }

    public function fetchBySensorId(int $sensorId): ?HumidityMeasurement
    {
        $json = file_get_contents($this->url . $sensorId);

        if ($json === false) {
            return null;
        }

        $data = json_decode($json, true);

        $humidity = $data['humidity'] ?? null;

        if (!is_numeric($humidity) || $humidity < 0 || $humidity > 100) {
            throw new Exception("Nieprawidłowa wartość wilgotności");
        }

        $humidity = (float)$humidity;

        return (new HumidityMeasurement())->setDateTime(date('Y-m-d H:i:s'))
                                            ->setHumidity($humidity)
                                            ->setSensorId($sensorId);
    }
}
