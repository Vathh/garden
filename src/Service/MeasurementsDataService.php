<?php

namespace App\Service;

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
            $temperaturesSum += $measurement['temperature'];
        }
        return round($temperaturesSum / $measurementsCount, 2);
    }

    public function getHighestTemperature(array $measurements): float
    {
        $highestTemperature = 0;
        foreach ($measurements as $measurement) {
            if ($measurement['temperature'] > $highestTemperature) {
                $highestTemperature = $measurement['temperature'];
            }
        }
        return round($highestTemperature, 2);
    }

    public function getHottestDay(array $measurements): array
    {
        $averages = [];

        foreach ($measurements as $measurement) {
            try {
                $date = (new DateTime($measurement['datetime']))->format('Y-m-d');
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            $temperature = $measurement['temperature'];

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
            if ($measurement['temperature'] < $lowestTemperature) {
                $lowestTemperature = $measurement['temperature'];
            }
        }
        return round($lowestTemperature, 2);
    }

    public function getAggregatedMeasurement(array $measurements): array
    {
        if (empty($measurements)) {
            return ['datetime' => null, 'temperature' => null];
        }

        $averageDateTime = $measurements[intval(count($measurements) / 2)]['datetime'] ?? null;
        $averageTemperature = $this->getAverageTemperature($measurements);

        return ['datetime' => $averageDateTime,
            'temperature' => $averageTemperature];
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
            $measurementDateTime = new DateTime($measurement['datetime']);

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
