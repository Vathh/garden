<?php

namespace App\Service;

use App\Model\TemperatureMeasurement;
use DateMalformedStringException;
use DateTime;
use Exception;

class MeasurementsDataService
{
    public function getAverageTemperature(array $measurements): float
    {
        $measurementsCount = count($measurements);
        $temperaturesSum = 0;
        foreach ($measurements as $measurement) {
            $temperaturesSum += $measurement->getTemperature();
        }
        return round($temperaturesSum / $measurementsCount, 2);
    }

    public function getHighestTemperature(array $measurements): float
    {
        $highestTemperature = 0;
        foreach ($measurements as $measurement) {
            if ($measurement->getTemperature() > $highestTemperature) {
                $highestTemperature = $measurement->getTemperature();
            }
        }
        return round($highestTemperature, 2);
    }

    public function getHottestDay(array $measurements): array
    {
        $averages = [];

        foreach ($measurements as $measurement) {
            try {
                $date = (new DateTime($measurement->getDateTime()))->format('Y-m-d');
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            $temperature = $measurement->getTemperature();

            if (!isset($averages[$date])) {
                $averages[$date] = ['sum' => 0, 'count' => 0];
            }

            $averages[$date]['sum'] += $temperature;
            $averages[$date]['count']++;
        }

        $hottestDate = null;
        $highestAverage = 0;

        foreach ($averages as $date => $rows) {
            $dailyAverage = $rows['sum'] / $rows['count'];
            if ($highestAverage < $dailyAverage) {
                $hottestDate = $date;
                $highestAverage = $dailyAverage;
            }
        }

        return ['date' => $hottestDate, 'average' => round($highestAverage, 2)];
    }

    public function getLowestTemperature(array $measurements): float
    {
        $lowestTemperature = 100;
        foreach ($measurements as $measurement) {
            if ($measurement->getTemperature() < $lowestTemperature) {
                $lowestTemperature = $measurement->getTemperature();
            }
        }
        return round($lowestTemperature, 2);
    }

    public function getAggregatedMeasurement(array $measurements): TemperatureMeasurement
    {
        $result = new TemperatureMeasurement();
        if (empty($measurements)) {
            return $result->setDatetime(null)->setTemperature((float)null);
        }

        $averageDateTime = $measurements[intval(count($measurements) / 2)]->getDateTime() ?? null;
        $averageTemperature = $this->getAverageTemperature($measurements);

        return $result->setDatetime($averageDateTime)->setTemperature($averageTemperature);
    }

    public function splitMeasurementsIntoSmallerGroups(array $measurements, int $splitArraysGroups): array
    {
        $splitArraySize = max(1, (int)floor(count($measurements) / $splitArraysGroups));

        return array_chunk($measurements, $splitArraySize);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function splitMeasurementsByDatetime(array $measurements): array
    {
        $oneDayBack = (new DateTime())->modify('-1 day');
        $oneWeekBack = (new DateTime())->modify('-7 days');
        $oneMonthBack = (new DateTime())->modify('-1 month');

        $lastDayMeasurements = [];
        $lastWeekMeasurements = [];
        $lastMonthMeasurements = [];

        foreach ($measurements as $measurement) {
            $measurementDateTime = new DateTime($measurement->getDateTime());

            if ($measurementDateTime >= $oneMonthBack) {
                $lastMonthMeasurements[] = $measurement;

                if ($measurementDateTime >= $oneWeekBack) {
                    $lastWeekMeasurements[] = $measurement;

                    if ($measurementDateTime >= $oneDayBack) {
                        $lastDayMeasurements[] = $measurement;
                    }
                }
            }
        }

        return [
            'lastDayMeasurements' => $lastDayMeasurements,
            'lastWeekMeasurements' => $lastWeekMeasurements,
            'lastMonthMeasurements' => $lastMonthMeasurements,
        ];
    }
}
