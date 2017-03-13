<?php

$config = require_once __DIR__ . '/config/config.php';

$dbConfig = $config['database'];

return [
    'paths' => [
        'migrations' => 'src/Database/Migration',
        'seeds'      => 'src/Database/Seed'
    ],

    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database'        => 'development',

        'development' => [
            'host'         => $dbConfig['host'],
            'adapter'      => $dbConfig['driver'],
            'name'         => $dbConfig['database'],
            'user'         => $dbConfig['username'],
            'pass'         => $dbConfig['password'],
            'charset'      => $dbConfig['charset'],
            'collation'    => $dbConfig['collation'],
            'table_prefix' => $dbConfig['prefix']
        ]
    ]
];
