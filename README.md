# le7-core

This package is core of le7 PHP MVC framework. You can use it in your own project.

## Requirements

- PHP 8.1 or higher.
- Composer 2.0 or higher.

## Basic usage
This is only core. To install this framework,
llease visit https://github.com/rnr1721/le7-framework for more information.
You must use le7 project skeleton to use it.

index.php in public folder
```php
<?php

define('PUBLIC_PATH', realpath(dirname(__FILE__)));

require_once '../bootstrap.php';
```

bootstrap.php
```php
declare(strict_types=1);

use Nyholm\Psr7Server\ServerRequestCreator;
use Core\Topology;
use Core\InitHttp;
use Core\InitCli;
use App\Classes\Factories\ContainerFactoryPhpDi;

if (!defined('PUBLIC_PATH')) {
    // Prevent to launch not from public folder
    echo 'Please run program from webroot folder' . PHP_EOL;
    exit;
}

// Set microtime for measuring page generation time
$start = microtime(true);

define('BASE_PATH', realpath(dirname(__FILE__)));

define('DS', DIRECTORY_SEPARATOR);

$diCompiledPath = BASE_PATH . DS . 'var' . DS . 'containers';
$diConfig = BASE_PATH . DS . 'config' . DS . 'di';

$loader = require('vendor' . DS . 'autoload.php');

$topology = new Topology(PUBLIC_PATH, BASE_PATH);

$containerFactory = new ContainerFactoryPhpDi($diConfig, $diCompiledPath);

if (php_sapi_name() === 'cli') {
    $init = new InitCli($containerFactory, $topology);
    $init->run();
} else {
    $init = new InitHttp($containerFactory, $topology);
    $factory = new Nyholm\Psr7\Factory\Psr17Factory();
    $creator = new ServerRequestCreator(
            $factory, // ServerRequestFactory
            $factory, // UriFactory
            $factory, // UploadedFileFactory
            $factory  // StreamFactory
    );
    $request = $creator->fromGlobals();
    $response = $init->run($request);
}
```
