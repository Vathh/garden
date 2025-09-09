<?php

namespace App\Service;

use App\Core\Database;
use App\Model\TemperatureMeasurement;
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
    private TemperatureService $temperatureService;
    private ChartService $chartService;

    public function __construct()
    {
        $this->temperatureService = new TemperatureService();
        $this->chartService = new ChartService();
    }

    public function generateExcelReport(string $savePath): void
    {
        $spreadSheet = new Spreadsheet();

        $spreadSheet->removeSheetByIndex(
            $spreadSheet->getIndex($spreadSheet->getActiveSheet())
        );

        $temperaturesGroupedByMonth = TemperatureMeasurement::fetchGroupedByMonth();

        foreach ($temperaturesGroupedByMonth as $month => $rows) {
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
            foreach ($rows as $measurement) {
                $sheet->setCellValue("A$i", $measurement->getDateTime());
                $sheet->setCellValue("B$i", $measurement->getTemperature());
                $i++;
            }

            $sheet->setCellValue('C2', count($rows));
            $sheet->setCellValue('D2', MeasurementsDataService::getAverageTemperature($rows));
            $sheet->setCellValue('E2', MeasurementsDataService::getHighestTemperature($rows));
            $sheet->setCellValue('F2', MeasurementsDataService::getLowestTemperature($rows));
            $sheet->setCellValue('G2', MeasurementsDataService::getHottestDay($rows)['date']);
            $sheet->setCellValue('G3', MeasurementsDataService::getHottestDay($rows)['average']);

            $writer = new Xlsx($spreadSheet);
            try {
                $writer->save($savePath);
            } catch (Exception $e) {
                echo "Błąd zapisu: " . $e->getMessage();
            }
        }
    }

    /**
     * @throws DateMalformedStringException
     * @throws MpdfException
     */
    public function generatePDFReport(string $path): void
    {
        $measurements = $this->temperatureService->getTemperatureDataForSelectedRange('30d');
        $splitMeasurements = MeasurementsDataService::splitMeasurementsByDatetime($measurements);
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
        $averageTemperature = MeasurementsDataService::getAverageTemperature($measurements);
        $maxTemperature = MeasurementsDataService::getHighestTemperature($measurements);
        $minTemperature = MeasurementsDataService::getLowestTemperature($measurements);
        $hottestDay = MeasurementsDataService::getHottestDay($measurements)['date'];
        $hottestDayAverage = MeasurementsDataService::getHottestDay($measurements)['average'];

        require __DIR__ . "/../../templates/report.html.php";

        $html = ob_get_clean();
        $mpdf->WriteHTML($html);

        $mpdf->Output($path, Destination::FILE);
//        $mpdf->Output('raport_test.pdf', Destination::INLINE);
    }
}
