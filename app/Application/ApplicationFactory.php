<?php

namespace App\Application;

use Boronczyk\LocalizationMiddleware;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ResponseFactoryInterface;
use RKA\Middleware\IpAddress;
use Shisa\Sofi\Application\ApplicationFactory as Base;
use Shisa\Sofi\Configurations\Configuration;
use Slim\App;
use Slim\Middleware\BodyParsingMiddleware;
use Slim\Psr7\Factory\ResponseFactory;

class ApplicationFactory extends Base
{
    /**
     * @param \DI\Container $container
     */
    protected static function configureContainer(App $app, Container $container)
    {
        $container->has(ResponseFactoryInterface::class)
            || $container->set(
                ResponseFactory::class,
                $app->getResponseFactory()
            );
    }

    /**
     * @param \DI\Container $container
     */
    protected static function configureMiddlewares(App $app, Container $container)
    {
        $debugMode = $container->get(Configuration::class)->debug;

        $app->add(BodyParsingMiddleware::class);
        $app->addRoutingMiddleware();
        $app->addErrorMiddleware($debugMode, true, true);

        // // Retrieving locale preference from client
        // $app->add(LocalizationMiddleware::class);

        // Retrieving IP address from client
        $app->add(IpAddress::class);

        // Allowing only accessing by designated hosts when not in debug mode.
        !$debugMode && $app->add(SafeHostRequestMiddleware::class);
    }

    /**
     * @param \DI\Container $container
     */
    protected static function registerRoutes(App $app, Container $container)
    {
        $app->get('/hello/[{name}/]', \App\Actions\HelloWorld::class);
    }
}
