<?php

namespace Shisa\Sofi\Application;

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

class ApplicationFactory
{
    public final static function create(ContainerInterface $container)
    {
        $app = AppFactory::createFromContainer($container);
        static::configureContainer($app, $container);
        static::configureMiddlewares($app, $container);
        static::registerRoutes($app, $container);
        return $app;
    }

    protected static function configureContainer(App $app, ContainerInterface $container)
    {
    }

    protected static function configureMiddlewares(App $app, ContainerInterface $container)
    {
    }

    protected static function registerRoutes(App $app, ContainerInterface $container)
    {
    }
}
