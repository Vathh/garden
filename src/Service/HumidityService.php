<?php

namespace App\Service;

use App\Model\HumidityMeasurement;

class HumidityService
{
    private String $url = 'https://link_do_czujnika/$sensorId';
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
        $json = file_get_contents($this->url);

        if ($json === false) {
            return null;
        }

        $data = json_decode($json, true);

        return (new HumidityMeasurement())->setDateTime(date('Y-m-d H:i:s'))
                                            ->setHumidity($data['humidity'])
                                            ->setSensorId($sensorId);
    }
}
