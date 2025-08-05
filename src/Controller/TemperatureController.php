<?php

namespace App\Controller;

use App\Core\Database;
use App\Service\TemperatureService;
use DateTime;
use DateTimeInterface;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use PDO;

class TemperatureController
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    #[NoReturn] public function getTemperatureChartDataJson(): void
    {
        $range = $_GET["range"] ?? '1h';

        $temperatureService = new TemperatureService();
        $data = $temperatureService->getTemperatureDataForSelectedRange($range);

        header('Content-type: application/json');
        echo json_encode($data);
        exit;
    }
}
