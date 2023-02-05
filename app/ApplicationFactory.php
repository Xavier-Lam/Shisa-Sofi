<?php

namespace App;

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
        $configuration = $container->get(Configuration::class);

        $container->has(ResponseFactoryInterface::class)
            || $container->set(
                ResponseFactory::class,
                $app->getResponseFactory()
            );

        $configuration->i18n->enabled
            && $container->set(
                LocalizationMiddleware::class,
                static function (Configuration $configuration) {
                    return new LocalizationMiddleware(
                        $configuration->i18n->locales,
                        $configuration->i18n->locales[0]
                    );
                }
            );
    }

    /**
     * @param \DI\Container $container
     */
    protected static function configureMiddlewares(App $app, Container $container)
    {
        $configuration = $container->get(Configuration::class);

        $app->add(BodyParsingMiddleware::class);
        $app->addRoutingMiddleware();
        $app->addErrorMiddleware($configuration->debug, true, true);

        // Retrieving locale preference from client
        $configuration->i18n->enabled
            && $app->add(LocalizationMiddleware::class);

        // Retrieving IP address from client
        $app->add(IpAddress::class);

        // Allowing only accessing by designated hosts when not in debug mode.
        !$configuration->debug
            && $app->add(SafeHostRequestMiddleware::class);
    }

    /**
     * @param \DI\Container $container
     */
    protected static function registerRoutes(App $app, Container $container)
    {
        $app->get('/hello/[{name}/]', Actions\HelloWorld::class);
    }
}
