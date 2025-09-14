<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\View;
use App\Model\Report;
use App\Model\TemperatureMeasurement;
use App\Model\Todo;
use App\Service\HumidityService;
use Exception;

class PagesController
{
    /**
     * @throws \DateMalformedStringException
     */
    public function showHomePage(): void
    {
        Auth::requireAuth();

        $userName = isset($_SESSION['user']) ? $_SESSION['user']->getLogin() : 'Użytkownik';
        $humidityMeasurements = HumidityService::fetchAll();

        $lowHumidity = [];
        $mediumHumidity = [];
        $highHumidity = [];

        foreach ($humidityMeasurements as $measurement) {
            if ($measurement->getHumidity() > 15) {
                if ($measurement->getHumidity() > 25) {
                    $highHumidity[] = $measurement;
                } else {
                    $mediumHumidity[] = $measurement;
                }
            } else {
                $lowHumidity[] = $measurement;
            }
        }

        $todos = Todo::getAllUndone();

        $splitTodos = Todo::splitByDeadline($todos);
        $urgentTodosCount = count($splitTodos['pastOrToday']);
        $nonUrgentTodosCount = count($splitTodos['future']);

        $lastMeasurement = TemperatureMeasurement::fetchLastMeasurement();

        $internalTemperature = $lastMeasurement !== null ?
            round($lastMeasurement->getTemperature(), 1) . "°C" : 'Brak danych';

        try {
            $todos = array_slice($todos, 0, 5);

            View::render('pages.home', [
                'userName' => $userName,
                'todos' => $todos,
                'lowHumidityCount' => count($lowHumidity),
                'mediumHumidityCount' => count($mediumHumidity),
                'highHumidityCount' => count($highHumidity),
                'urgentTodosCount' => $urgentTodosCount,
                'nonUrgentTodosCount' => $nonUrgentTodosCount,
                'internalTemperature' => $internalTemperature
            ]);
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    public function showAccountMenuPage(): void
    {
        Auth::requireAuth();

        try {
            View::render('pages.accountMenu');
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    public function showReportsPage(): void
    {
        Auth::requireAuth();

        $reportsDir = __DIR__ . '/../../public/report-files';
        $url = '/report-files';

        $perPageOptions = [3, 10, 20, 100];
        $defaultPerPageOption = 10;

        $perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $perPageOptions)
            ? (int)$_GET['per_page']
            : $defaultPerPageOption;

        $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $files = array_filter(
            scandir($reportsDir),
            fn($file) => str_ends_with($file, '.xlsx') || str_ends_with($file, '.pdf')
        );

        $reports = [];

        foreach ($files as $file) {
            if (preg_match('/temperature_report_(\d{8})_\d{6}\.(pdf|xlsx)/', $file, $matches)) {
                if ($matches) {
                    $date = $matches[1];
                    $extension = $matches[2];

                    if (!isset($reports[$date])) {
                        $reports[$date] = new Report($date);
                    }

                    if ($extension == 'pdf') {
                        $reports[$date]->setPdfPath($file);
                    }
                    if ($extension == 'xlsx') {
                        $reports[$date]->setXlsxPath($file);
                    }
                }
            }
        }

        usort($reports, fn(Report $a, Report $b) => strcmp($b->getDate(), $a->getDate()));


        $total = count($reports);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;


        $visibleReports = array_slice($reports, $offset, $perPage);

        try {
            View::render('pages.reports', [
                'reports' => $visibleReports,
                'url' => $url,
                'page' => $page,
                'perPage' => $perPage,
                'totalPages' => $totalPages,
                'perPageOptions' => $perPageOptions,
            ]);
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    public function deleteReportFile(): void
    {
        Auth::requireAuth();

        if (!isset($_POST['fileName'])) {
            echo 'Brak takiego pliku.';
            return;
        }

        $fileName = basename($_POST['fileName']);
        $filePath = __DIR__ . '/../../public/report-files/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        header('Location: /reports');
        exit;
    }
}
