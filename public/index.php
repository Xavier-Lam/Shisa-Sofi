<?php

namespace App;

error_reporting(0);

// The root directory of your project.
define('ROOT_DIR', realpath(dirname(__DIR__)));

// The root directory of your application
define('APP_DIR', realpath(ROOT_DIR . DIRECTORY_SEPARATOR . 'app'));

require ROOT_DIR . '/vendor/autoload.php';

$envs = $_ENV + $_SERVER;
// The environment of your application
define('APP_ENV', Bootstrap::determineEnvironment($envs));

$configuration = Bootstrap::getConfiguration(APP_ENV, $envs);

$container = Bootstrap::getContainer($configuration);

if (php_sapi_name() === 'cli') {
    $app = ConsoleFactory::create($container);

    $code = $app->run();

    exit($code);
} else {
    $app = ApplicationFactory::create($container);

    $app->run();
}
