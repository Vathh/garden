<?php

namespace App\Service;

use App\Core\Database;
use DateTime;
use Exception;
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
            } catch (\Exception $e) {
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

    private function getChartPng(array $measurements)
    {
        $char  = new QuickChart();

        $config = <<<EOD
        {
            type: 'line',
            data: {
                labels: []
            }
        }
        EOD;

    }

//        {type:'line',
//            data:
//            {labels:['January','February','March','April','May'],
//                datasets:[
//                    {label:'Dogs',
//                    data:[50,60,70,180,190],
//                    fill:false,
//                    borderColor:'blue'},
//                {label:'Cats',
//                    data:[100,200,300,400,500],
//                    fill:false,
//                    borderColor:'green'}]}}">}
}
