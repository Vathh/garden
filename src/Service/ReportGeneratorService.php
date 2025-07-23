<?php

namespace App\Service;

use App\Core\Database;
use DateTime;
use Exception;
use PDO;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ReportGeneratorService
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function generateExcelReport(string $savePath): void
    {
        $spreadSheet = new Spreadsheet();
        $temperatures = $this->fetchTemperaturesGroupedByMonth();
    }

    private function fetchTemperaturesGroupedByMonth(): array
    {
        $stmt = $this->conn->prepare("
            SELECT value, created_at
            FROM temperatures
            ORDER BY created_at ASC
        ");

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
}
