<?php

namespace App\Service;

use DateTime;
use Exception;
use QuickChart;

class ChartService
{
    private MeasurementsDataService $measurementsDataService;

    public function __construct()
    {
        $this->measurementsDataService = new MeasurementsDataService();
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
            $splitMeasurements = $this->measurementsDataService
                                    ->splitMeasurementsIntoSmallerGroups($measurements, $chartPointsCount);

            foreach ($splitMeasurements as $chunk) {
                $finalMeasurements[] = $this->measurementsDataService->getAggregatedMeasurement($chunk);
            }
        } else {
            $finalMeasurements = $measurements;
        }

        $labels = array_map(function ($item) {
            try {
                $datetime = new DateTime($item);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            return $datetime->format('H:i:s') . "\n" . $datetime->format('Y-m-d');
        }, array_column($finalMeasurements, 'datetime'));

        $reducedLabels = $this->reduceLabels($labels, 5);

        $temperatures = array_column($finalMeasurements, 'temperature');

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
