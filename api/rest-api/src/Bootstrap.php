<?php

namespace App;

use DI\Bridge\Slim\App as SlimApp;
use DI\ContainerBuilder;
use function DI\object;
use function DI\get;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Exception;

class Bootstrap extends SlimApp
{
    const CONFIG_DIR = __DIR__ . '/../config/';

    protected function configureContainer(ContainerBuilder $builder)
    {
        // Slim Settings.
        $builder->addDefinitions(
            [
                'settings.displayErrorDetails' => true
            ]
        );

        // App Settings.
        $builder->addDefinitions(
            [ 
                'config' => require_once $this::CONFIG_DIR . 'config.php' 
            ]
        );

        // Load Dependencies.
        $builder->addDefinitions($this::CONFIG_DIR . 'dependencies.php');
    }

    public function initRoutes()
    {
        try {
            $routesFile = $this::CONFIG_DIR . 'routes.yaml';
            $routes = Yaml::parse(file_get_contents($routesFile));
        } catch (ParseException $exception) {
            throw new ParseException($exception->getMessage());
        }

        foreach($routes as $route) {
            if (isset($route['children'])) {
                $this->group($route['uri'], function() use ($route) {
                    foreach($route['children'] as $childRoute) {
                        $this->map(
                            $childRoute['methods'], 
                            ltrim($childRoute['uri'], '/'), 
                            $this->parseRouteController($childRoute['controller'])
                        );
                    }
                });

                continue;
            }

            $this->map(
                $route['methods'], 
                $route['uri'], 
                $this->parseRouteController($route['controller'])
            );
        }
    }

    protected function parseRouteController($controller)
    {
        $namespace = 'App\Controller\\';

        if(!isset($controller[1])) {
            return $namespace . $controller[0];
        }

        return [$namespace . $controller[0], $controller[1]];
    }
}
