<?php

namespace App\Model;

use App\Core\Database;
use App\Service\MeasurementsDataService;
use DateTime;
use DateTimeInterface;
use Exception;
use PDO;

class TemperatureMeasurement
{
    public string $dateTime;
    public float $temperature;

    public function __construct()
    {
    }

    public function getDatetime(): string
    {
        return $this->dateTime;
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

    public static function fetchGroupedByMonth(): array
    {
        $conn = Database::getInstance()->getConnection();

        $stmt = $conn->prepare("
            SELECT value, created_at
            FROM temperatures
            ORDER BY created_at ASC
        ");

        $stmt->execute();

        $temperaturesGroupedByMonth = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            try {
                $dateTime = new DateTime($row['created_at']);
                $month = $dateTime->format('Y-m');

                $measurement = new TemperatureMeasurement();

                $temperaturesGroupedByMonth[$month][] = $measurement->setTemperature($row['value'])
                    ->setDatetime($dateTime->format('Y-m-d H:i:s'));
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        return $temperaturesGroupedByMonth;
    }

    public static function addToDbWithActualDate(int $sensorId, int $value): void
    {
        $conn = Database::getInstance()->getConnection();

        $query = "
            INSERT INTO temperatures (sensor_id, value, created_at) VALUES (?, ?, ?);                                                            
        ";

        $stmt = $conn->prepare($query);
        $stmt->execute([$sensorId, $value, date('Y-m-d H:i:s')]);
    }

    public static function fetchFromLastPeriodAndAggregate($interval): array
    {
        $conn = Database::getInstance()->getConnection();

        $query = "
            SELECT value, created_at
            FROM temperatures
            WHERE created_at >= NOW() - INTERVAL $interval
            ORDER BY created_at ASC
        ";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $measurement = new TemperatureMeasurement();
            try {
                $data[] = $measurement->setDatetime((new DateTime($row['created_at']))->format(DateTimeInterface::ATOM))
                                      ->setTemperature(floatval($row['value']));
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        return MeasurementsDataService::aggregateMeasurements($data, 100);
    }

    public static function fetchLastMeasurement(): ?TemperatureMeasurement
    {
        $conn = Database::getInstance()->getConnection();

        $stmt = $conn->prepare("SELECT value, created_at FROM temperatures ORDER BY created_at DESC LIMIT 1");
        $stmt->execute();

        $measurementData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$measurementData) {
            return null;
        }

        $measurement = new TemperatureMeasurement();

        try {
            $measurement->setDatetime((new DateTime($measurementData['created_at']))->format(DateTimeInterface::ATOM))
                ->setTemperature(floatval($measurementData['value']));
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $measurement;
    }
}
