<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\ZonesController;
use App\Controller\TemperatureController;
use App\Core\Router;
use App\Controller\AuthController;
use App\Controller\PagesController;
use App\Service\ReportGeneratorService;

session_start();

$router = new Router();


$router->get('/', [PagesController::class, 'showHomePage']);

$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);

$router->get('/changePassword', [AuthController::class, 'showChangePasswordForm']);
$router->post('/changePassword', [AuthController::class, 'changePassword']);

$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/activate', [AuthController::class, 'activate']);

$router->get('/register', [AuthController::class, 'showRegisterForm']);
$router->post('/register', [AuthController::class, 'register']);

$router->get('/zones', [ZonesController::class, 'showZonesMenuPage']);
$router->get('/zones/greenhouse', [ZonesController::class, 'showGreenHousePage']);
$router->get('/zones/toolroom', [ZonesController::class, 'showToolroomPage']);

$router->get('/account', [PagesController::class, 'showAccountMenuPage']);

$router->get('/temperature', [TemperatureController::class, 'getTemperatureChartDataJson']);

$router->get('/reports', [PagesController::class, 'showReportsPage']);

//$router->get('/podglad-raportu', [ReportGeneratorService::class, 'generatePDFReport']);


echo $router->resolve();
