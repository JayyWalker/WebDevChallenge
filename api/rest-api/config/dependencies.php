<?php

use Interop\Container\ContainerInterface;
use function DI\object;
use function DI\get;

return [
    \Doctrine\ORM\EntityManager::class => function(ContainerInterface $c) {

        return (new \App\Service\DatabaseService)->create($c->get('config'));
    },
    'EntityManager' => get(\Doctrine\ORM\EntityManager::class),

    \App\Environment::class => function(ContainerInterface $c) {
        $environment = new \App\Environment;

        $config = $c->get('config');

        return $environment->validateEnvironment($config['environment']);
    },

    'errorHandler' => object(\App\Handler\ErrorHandler::class),

    'phpErrorHandler' => object(\App\Handler\PhpErrorHandler::class),

    'notFoundHandler' => object(\App\Handler\NotFoundHandler::class),

    'notAllowedHandler' => object(\App\Handler\NotAllowedHandler::class)
];
