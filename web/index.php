<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Itb\WebApplication;

session_start();

$app = new WebApplication();

$app->run();