<?php

declare(strict_types=1);

use App\Core\Instances\InstanceCliData;
use App\Core\Instances\InstanceCli;
use App\Core\Instances\InstanceHttp;
use App\Core\Instances\InstanceHttpData;
use App\Core\Php;
use App\Core\Config\TopologyFs;
use App\Core\Config\ConfigFromObject;
use App\Core\ErrorHandling\ErrorLogInterface;
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

// Set errors reporting
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('CORE_PATH', realpath(dirname(__FILE__)));

const REDBEAN_MODEL_PREFIX = 'App\\Model\\';

$config = new ConfigFromObject(BASE_PATH);
$topology = new TopologyFs(BASE_PATH, CORE_PATH, PUBLIC_PATH, $config);

/** @var ContainerInterface $container */
$container = require $topology->getCorePath() . '/dependencies.php';

try {
    $errorLog = $container->get(ErrorLogInterface::class);
    $log = $container->get(LoggerInterface::class);
    $php = $container->get(Php::class);
} catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
    die($e->getMessage());
}

$php->setTimeZone($config->getTimeZone());

if (php_sapi_name() === 'cli') {
    /** @var InstanceCliData $instanceCliData */
    $instanceCliData = $container->get(InstanceCliData::class);
    $route = $instanceCliData->getCurrentRoute();
    $instance = $container->get(InstanceCli::class);
} else {
    /** @var InstanceHttpData $instanceHttpData */
    $instanceHttpData = $container->get(InstanceHttpData::class);
    $route = $instanceHttpData->getCurrentRoute();
    $instance = $container->get(InstanceHttp::class);
}

// start the instance and get route runner
$runner = $instance->startInstance($route);

// Run route
$runner->run($route);

exit;
