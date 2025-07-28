<?php

namespace App\Service;

use App\Core\Database;
use DateTime;
use Exception;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use PDO;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use QuickChart;

class ReportGeneratorService
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function generateExcelReport(string $savePath): void
    {
        if (!is_writable(dirname($savePath))) {
            echo "Folder nie ma uprawnień do zapisu: " . dirname($savePath) . "\n";
        } else {
            echo "Folder jest zapisywalny" . dirname($savePath) . "\n";
        }

        $spreadSheet = new Spreadsheet();
        $temperatures = $this->fetchTemperaturesGroupedByMonth();

        foreach ($temperatures as $month => $rows) {
            $sheet = $spreadSheet->createSheet();
            $sheet->setTitle($month);

            $headers = ['Czas', 'Temperatura', 'Ilość pomiarów', 'Średnia temperatura', 'Najwyższa temperatura', 'Najniższa temperatura', 'Najcieplejszy dzień'];
            $sheet->fromArray($headers, null, 'A1');
            $sheet->setAutoFilter('A1:G1');

            $headerColumn = 'A';
            foreach ($headers as $header) {
                $sheet->getColumnDimension($headerColumn)->setAutoSize(true);
                $headerColumn++;
            }

            $i = 2;
            foreach ($rows as $row) {
                $sheet->setCellValue("A$i", $row['created_at']);
                $sheet->setCellValue("B$i", $row['temperature']);
                $i++;
            }

            $sheet->setCellValue('C2', count($rows));
            $sheet->setCellValue('D2', $this->getAverageTemperature($rows));
            $sheet->setCellValue('E2', $this->getHighestTemperature($rows));
            $sheet->setCellValue('F2', $this->getLowestTemperature($rows));
            $sheet->setCellValue('G2', $this->getHottestDay($rows)['date']);
            $sheet->setCellValue('G3', $this->getHottestDay($rows)['average']);

            $emptySheetIndex = $spreadSheet->getIndex(
                $spreadSheet->getSheetByName('WorkSheet')
            );
            $spreadSheet->removeSheetByIndex($emptySheetIndex);

            $writer = new Xlsx($spreadSheet);
            try {
                $writer->save($savePath);
            } catch (Exception $e) {
                echo "Błąd zapisu: " . $e->getMessage();
            }
        }
    }

    private function getAverageTemperature(array $measurements): float
    {
        $measurementsCount = count($measurements);
        $temperaturesSum = 0;
        foreach ($measurements as $measurement) {
            $temperaturesSum += $measurement['temperature'];
        }
        return $temperaturesSum / $measurementsCount;
    }

    private function getHighestTemperature(array $measurements): float
    {
        $highestTemperature = 0;
        foreach ($measurements as $measurement) {
            if ($measurement['temperature'] > $highestTemperature) {
                $highestTemperature = $measurement['temperature'];
            }
        }
        return $highestTemperature;
    }

    private function getHottestDay(array $measurements): array
    {
        $averages = [];

        foreach ($measurements as $measurement) {
            try {
                $date = (new DateTime($measurement['created_at']))->format('Y-m-d');
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

        return ['date' => $hottestDate, 'average' => $highestAverage];
    }

    private function getLowestTemperature(array $measurements): float
    {
        $lowestTemperature = 100;
        foreach ($measurements as $measurement) {
            if ($measurement['temperature'] < $lowestTemperature) {
                $lowestTemperature = $measurement['temperature'];
            }
        }
        return $lowestTemperature;
    }

    private function fetchTemperaturesGroupedByMonth(): array
    {
        $stmt = $this->conn->prepare("
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

                $temperaturesGroupedByMonth[$month][] = [
                    'temperature' => $row['value'],
                    'created_at' => $dateTime->format('Y-m-d H:i:s')
                ];
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        return $temperaturesGroupedByMonth;
    }

    private function getAggregatedMeasurement(array $measurements): array
    {
        if (empty($measurements)) {
            return ['datetime' => null, 'temperature' => null];
        }

        $averageDateTime = $measurements[intval(count($measurements) / 2)]['datetime'] ?? null;
        $averageTemperature = $this->getAverageTemperature($measurements);

        return ['datetime' => $averageDateTime,
            'temperature' => $averageTemperature];
    }

    private function splitMeasurementsIntoSmallerGroups(array $measurements, int $splitArraysGroups): array
    {
        $splitArraySize = max(1, (int)floor(count($measurements) / $splitArraysGroups));

        return array_chunk($measurements, $splitArraySize);
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

    private function getChartPng(array $measurements, int $chartPointsCount) : string
    {
        $finalMeasurements = [];

        if (count($measurements) > $chartPointsCount) {
            $splitMeasurements = $this->splitMeasurementsIntoSmallerGroups($measurements, $chartPointsCount);

            foreach ($splitMeasurements as $chunk) {
                $finalMeasurements[] = $this->getAggregatedMeasurement($chunk);
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

    /**
     * @throws \DateMalformedStringException
     */
    private function splitMeasurementsByDatetime(array $measurements): array
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
                    $lastWeekMeasurements = $measurement;

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

    /**
     * @throws \DateMalformedStringException
     * @throws MpdfException
     */
    public function generatePDFReport(array $measurements)
    {
        $splitMeasurements = $this->splitMeasurementsByDatetime($measurements);

        $imageTags = [];

        foreach ($splitMeasurements as $measurementsGroup) {
            if (!empty($measurementsGroup)) {
                $binaryImage = base64_encode($this->getChartPng($measurementsGroup, 200));
                $imageTags[] = '<img src="data:image/png;base64,' . $binaryImage . '" />';
            }
        }

        $mpdf = new Mpdf();
        $mpdf->WriteHTML('<h1>Raport temperatur</h1>');
        foreach ($imageTags as $imageTag) {
            $mpdf->WriteHTML($imageTag);
        }

        $mpdf->Output(__DIR__ . '/../../storage/reports/raport_temperatur.pdf', Destination::FILE);
    }
}
