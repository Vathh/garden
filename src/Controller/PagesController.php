<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\View;
use Exception;

class PagesController
{
    public function showHomePage(): void
    {
        Auth::requireAuth();

        try {
            View::render('pages.home');
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

        $reportsDir = __DIR__ . '/../../storage/reports';
        $url = '/storage/reports';

        $perPageOptions = [3, 10, 20, 100];
        $defaultPerPageOption = 20;

        $perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $perPageOptions)
            ? (int)$_GET['per_page']
            : $defaultPerPageOption;

        $page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $excelFiles = array_filter(scandir($reportsDir), fn($file) => str_ends_with($file, '.xlsx'));
        $pdfFiles = array_filter(scandir($reportsDir), fn($file) => str_ends_with($file, '.pdf'));
        rsort($excelFiles);
        rsort($pdfFiles);

        $total = count($excelFiles);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $visibleExcelFiles = array_slice($excelFiles, $offset, $perPage);
        $visiblePdfFiles = array_slice($pdfFiles, $offset, $perPage);

        try {
            View::render('pages.reports', [
                'excelFiles' => $visibleExcelFiles,
                'pdfFiles' => $visiblePdfFiles,
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
}
