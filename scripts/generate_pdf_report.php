<?php

require_once __DIR__ .  "/../vendor/autoload.php";

use App\Service\ReportGeneratorService;

$generator = new ReportGeneratorService();

$path = __DIR__ . '/../storage/reports/temperature_pdf_report_' . date('Ymd_His') . '.pdf';

try {
    $generator->generatePDFReport($path);
} catch (Exception $e) {
    echo $e->getMessage();
}
