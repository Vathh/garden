<?php

require_once __DIR__ .  "/../vendor/autoload.php";

use App\Service\ReportGeneratorService;

$generator = new ReportGeneratorService();
$tempService = new \App\Service\TemperatureService();

$data = $tempService->getTemperatureDataForSelectedRange('30d');
try {
    $generator->generatePDFReport($data);
} catch (Exception $e) {
    echo $e->getMessage();
}
