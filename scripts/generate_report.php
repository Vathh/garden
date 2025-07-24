<?php

require_once __DIR__ .  "/../vendor/autoload.php";

use App\Service\ReportGeneratorService;

$generator = new ReportGeneratorService();

$path = __DIR__ . '/../storage/reports/temperature_report_' . date('Ymd_His') . '.xlsx';

$generator->generateExcelReport($path);
