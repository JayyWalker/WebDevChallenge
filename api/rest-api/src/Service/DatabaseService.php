<?php

namespace App\Service;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;

class DatabaseService
{
    public function create(array $config)
    {
        $dbConfig = $config['database'];
        $srcDBPath = ABSOLUTE_PATH . '/src/Database/';

        $mappingPaths = [
            $srcDBPath . 'Mapping' => 'App\Database\Entity',
        ];

        $isDevMode = $config['environment'] === 'development' ? true : false;

        $connection = [
            'driver'   => 'pdo_' . $dbConfig['driver'],
            'user'     => $dbConfig['username'],
            'password' => $dbConfig['password'],
            'dbname'   => $dbConfig['database']
        ];

        if ($isDevMode) {
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        } else {
            $cache = new \Doctrine\Common\Cache\ApcCache;
        }

        $YAMLDriver = new SimplifiedYamlDriver($mappingPaths);

        $doctrineConfig = new Configuration;
        $doctrineConfig->setMetadataCacheImpl($cache);
        $doctrineConfig->setMetadataDriverImpl($YAMLDriver);
        $doctrineConfig->setQueryCacheImpl($cache);
        $doctrineConfig->setProxyDir($srcDBPath . '/Proxy');
        $doctrineConfig->setProxyNamespace('App\Database\Proxy');

        if($isDevMode) {
            $doctrineConfig->setAutoGenerateProxyClasses(true);
        } else {
            $doctrineConfig->setAutoGenerateProxyClasses(false);
        }

        return EntityManager::create($connection, $doctrineConfig);
    }
}
