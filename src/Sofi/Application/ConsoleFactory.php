<?php

namespace Shisa\Sofi\Application;

use Psr\Container\ContainerInterface;
use Shisa\Sofi\Configurations\Configuration;
use Symfony\Component\Console\Application;

class ConsoleFactory
{
    public static function create(ContainerInterface $container)
    {
        $configuration = $container->get(Configuration::class);
        $app = new Application(
            $configuration->application['name'],
            $configuration->application['version']
        );
        static::configureConsole($app, $container);
        return $app;
    }

    protected static function configureConsole(Application $app, ContainerInterface $container)
    {
    }
}
