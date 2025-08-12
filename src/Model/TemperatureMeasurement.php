<?php

namespace App\Model;

class TemperatureMeasurement
{
    public string $dateTime;
    public float $temperature;

    public function __construct()
    {
    }

    public function getDatetime(): string
    {
        return $this->datetime;
    }

    public function setDatetime(string $dateTime): TemperatureMeasurement
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): TemperatureMeasurement
    {
        $this->temperature = $temperature;
        return $this;
    }
}