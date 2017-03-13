<?php

// Doctrine CLI

require_once 'vendor/autoload.php';

use App\Bootstrap;
use App\Service\DatabaseService;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

$app = new Bootstrap;

$entityManager = $app->getContainer()->get('EntityManager');

return ConsoleRunner::createHelperSet($entityManager);
