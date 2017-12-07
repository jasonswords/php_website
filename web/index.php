<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$logger = new Logger('Logger Name');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logger/log.txt', Logger::DEBUG));
$logger->info('Something just happened');
//$logger->error("An error has just Occurred");

use Itb\WebApplication;

session_start();

$app = new WebApplication();

$app->run();