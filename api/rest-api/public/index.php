<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Bootstrap;

$app = new Bootstrap;

$app->initRoutes();

$app->run();

