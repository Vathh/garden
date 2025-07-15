<?php

require_once __DIR__ .  "/../vendor/autoload.php";

use App\Service\TemperatureService;

$fetcher = new TemperatureService();

$fetcher->readTemperatureAndSaveToDb();
