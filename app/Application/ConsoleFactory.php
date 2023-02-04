<?php

namespace App\Application;

use Psr\Container\ContainerInterface;
use Shisa\Sofi\Application\ConsoleFactory as Base;
use Symfony\Component\Console\Application;

class ConsoleFactory extends Base
{
    protected static function configureConsole(Application $app, ContainerInterface $container)
    {
    }
}
