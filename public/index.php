<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\ZonesController;
use App\Core\Router;
use App\Controller\AuthController;
use App\Controller\HomeController;

session_start();

$router = new Router();


$router->get('/', [HomeController::class, 'loadHomePage']);

$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);

$router->get('/changePassword', [AuthController::class, 'showChangePasswordForm']);
$router->post('/changePassword', [AuthController::class, 'changePassword']);

$router->post('/logout', [AuthController::class, 'logout']);

$router->post('/activate', [AuthController::class, 'activate']);

$router->post('/register', [AuthController::class, 'register']);

$router->get('/zones', [ZonesController::class, 'showZonesMenuPage']);
$router->get('/zones/greenhouse', [ZonesController::class, 'showGreenHousePage']);
$router->get('/zones/toolroom', [ZonesController::class, 'showToolroomPage']);

echo $router->resolve();
