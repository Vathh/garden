<?php

require_once __DIR__ .  "/../vendor/autoload.php";

use App\Service\ReportGeneratorService;

$generator = new ReportGeneratorService();

$excelPath = __DIR__ . '/../storage/reports/temperature_excel_report_' . date('Ymd_His') . '.xlsx';

$generator->generateExcelReport($excelPath);

$pdfPath = __DIR__ . '/../storage/reports/temperature_pdf_report_' . date('Ymd_His') . '.pdf';

try {
    $generator->generatePDFReport($pdfPath);
} catch (Exception $e) {
    echo $e->getMessage();
}
