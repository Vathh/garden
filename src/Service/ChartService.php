<?php

namespace App\Service;

use App\Model\TemperatureMeasurement;
use DateTime;
use Exception;
use QuickChart;

class ChartService
{
    public function __construct()
    {
    }

    private function reduceLabels(array $labels, int $reducedLabelsCount): array
    {
        $step = intval(count($labels) / $reducedLabelsCount + 1);

        $reducedLabels = [];

        foreach ($labels as $i => $label) {
            if ($i % $step === 0) {
                $reducedLabels[] = $label;
            } else {
                $reducedLabels[] = '';
            }
        }

        return $reducedLabels;
    }

    public function getChartPng(array $measurements, int $chartPointsCount) : string
    {
        $finalMeasurements = [];

        if (count($measurements) > $chartPointsCount) {
            $finalMeasurements = MeasurementsDataService::aggregateMeasurements($measurements, $chartPointsCount);
        } else {
            $finalMeasurements = $measurements;
        }

        $labels = array_map(function (TemperatureMeasurement $measurement) {
            try {
                $datetime = new DateTime($measurement->getDateTime());
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            return $datetime->format('H:i:s') . "\n" . $datetime->format('Y-m-d');
        }, $finalMeasurements);

        $reducedLabels = $this->reduceLabels($labels, 5);

        $temperatures = array_map(fn(TemperatureMeasurement $n) => $n->getTemperature(), $finalMeasurements);

        $chart = new QuickChart(array(
            'width' => 1000,
            'height' => 600,
        ));

        $chart->setConfig([
            'type' => 'line',
            'data' => [
                'labels' => $reducedLabels,
                'datasets' => [[
                    'label' => 'Temperatura',
                    'data' => $temperatures,
                    'fill' => 'false',
                ]]
            ],
            'options' => [
                'responsive' => true,
                'layout' => [
                    'padding' => [
                        'bottom' => 40
                    ]
                ],
                'scales' => [
                    'x' => [
                        'ticks' => [
                            'autoSkip' => true,
                            'maxTicksLimit' => 20,
                            'maxRotation' => 0,
                            'minRotation' => 0,
                        ]
                    ]
                ]
            ]
        ]);

        try {
            return $chart->toBinary();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return '';
    }
}
