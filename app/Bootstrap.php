<?php

namespace App;

use Psr\Container\ContainerInterface;
use Shisa\Sofi\Application\Bootstrap as Base;

class Bootstrap extends Base
{
    /**
     * @var \DI\Container $container
     */
    protected static function configureContainer(ContainerInterface $container)
    {
    }
}
