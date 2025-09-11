<?php

namespace App\Model;

class HumidityMeasurement
{
    public string $dateTime;
    public float $humidity;
    public int $sensorId;

    public function __construct()
    {
    }

    public function getDateTime(): string
    {
        return $this->dateTime;
    }

    public function setDateTime(string $dateTime): HumidityMeasurement
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function getHumidity(): float
    {
        return $this->humidity;
    }

    public function setHumidity(float $humidity): HumidityMeasurement
    {
        $this->humidity = $humidity;
        return $this;
    }

    public function getSensorId(): int
    {
        return $this->sensorId;
    }

    public function setSensorId(int $sensorId): HumidityMeasurement
    {
        $this->sensorId = $sensorId;
        return $this;
    }
}
