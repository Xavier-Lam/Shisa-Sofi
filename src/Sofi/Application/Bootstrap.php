<?php

namespace Shisa\Sofi\Application;

use DI\Container;
use Psr\Container\ContainerInterface;
use Shisa\Sofi\Configurations\Configuration;

class Bootstrap
{
    public static function determineEnvironment(array $envs = [])
    {
        return isset($envs['APPLICATION_ENVIRONMENT']) ?
            $envs['APPLICATION_ENVIRONMENT']
            : 'Debug';
    }

    public static function getConfiguration(string $environment, array $envs): Configuration
    {
        $bootstrap = new \ReflectionClass(static::class);
        $ns = $bootstrap->getNamespaceName();
        $cls = implode('\\', [$ns, 'Configurations', $environment]);

        if (!class_exists($cls) || !is_subclass_of($cls, Configuration::class)) {
            // TODO: exception
            die('Incorrect application environment.');
        }

        return new $cls($envs);
    }

    /**
     * Get container based on configuration
     * 
     * @return Container
     */
    public static function getContainer(Configuration $configuration): ContainerInterface
    {
        static $containers = [];
        $objHash = spl_object_hash($configuration);
        if (!isset($containers[$objHash])) {
            $container = new Container();

            $container->set(Configuration::class, $configuration);
            $container->set('settings', $configuration);

            static::configureContainer($container);

            $containers[$objHash] = $container;
        }
        return $containers[$objHash];
    }

    protected static function configureContainer(ContainerInterface $container)
    {
    }
}
