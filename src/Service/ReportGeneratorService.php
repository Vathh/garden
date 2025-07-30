<?php

namespace App\Service;

use App\Core\Database;
use DateMalformedStringException;
use DateTime;
use Exception;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use PDO;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportGeneratorService
{
    private PDO $conn;
    private TemperatureService $temperatureService;
    private MeasurementsDataService $measurementsDataService;
    private ChartService $chartService;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
        $this->temperatureService = new TemperatureService();
        $this->measurementsDataService = new MeasurementsDataService();
        $this->chartService = new ChartService();
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

            $headers = ['Czas',
                        'Temperatura',
                        'Ilość pomiarów',
                        'Średnia temperatura',
                        'Najwyższa temperatura',
                        'Najniższa temperatura',
                        'Najcieplejszy dzień'];
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
            $sheet->setCellValue('D2', $this->measurementsDataService->getAverageTemperature($rows));
            $sheet->setCellValue('E2', $this->measurementsDataService->getHighestTemperature($rows));
            $sheet->setCellValue('F2', $this->measurementsDataService->getLowestTemperature($rows));
            $sheet->setCellValue('G2', $this->measurementsDataService->getHottestDay($rows)['date']);
            $sheet->setCellValue('G3', $this->measurementsDataService->getHottestDay($rows)['average']);

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

    /**
     * @throws DateMalformedStringException
     * @throws MpdfException
     */
    public function generatePDFReport(string $path): void
    {
        $measurements = $this->temperatureService->getTemperatureDataForSelectedRange('30d');
        $splitMeasurements = $this->measurementsDataService->splitMeasurementsByDatetime($measurements);
        $mpdf = new Mpdf();

        ob_start();

        $base64DayChart = '';
        $base64WeekChart = '';
        $base64MonthChart = '';

        if ($splitMeasurements['lastDayMeasurements'] != null) {
            $base64DayChart = base64_encode($this->chartService
                                                ->getChartPng($splitMeasurements['lastDayMeasurements'], 200));
        }
        if ($splitMeasurements['lastWeekMeasurements'] != null) {
            $base64WeekChart = base64_encode($this->chartService
                                                ->getChartPng($splitMeasurements['lastWeekMeasurements'], 200));
        }if ($splitMeasurements['lastMonthMeasurements'] != null) {
            $base64MonthChart = base64_encode($this->chartService
                                                ->getChartPng($splitMeasurements['lastMonthMeasurements'], 200));
        }

        $totalMeasurements = count($measurements);
        $averageTemperature = $this->measurementsDataService->getAverageTemperature($measurements);
        $maxTemperature = $this->measurementsDataService->getHighestTemperature($measurements);
        $minTemperature = $this->measurementsDataService->getLowestTemperature($measurements);
        $hottestDay = $this->measurementsDataService->getHottestDay($measurements)['date'];
        $hottestDayAverage = $this->measurementsDataService->getHottestDay($measurements)['average'];

        require __DIR__ . "/../../templates/report.html.php";

        $html = ob_get_clean();
        $mpdf->WriteHTML($html);

        $mpdf->Output($path, Destination::FILE);
//        $mpdf->Output('raport_test.pdf', Destination::INLINE);
    }
}
