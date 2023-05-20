<?php

declare(strict_types=1);

namespace Core\Interfaces;

use Core\ErrorHandler\ErrorHandlerCli;
use Core\ErrorHandler\ErrorHandlerHttp;
use Core\Interfaces\RouteHttpInterface;

interface ErrorHandlerFactoryInterface
{

    /**
     * Get configured Error Handler for command line
     * @return ErrorHandlerCli
     */
    public function getErrorHandlerCli(): ErrorHandlerCli;

    /**
     * Get configured Error Handler for http
     * @param RouteHttpInterface $route Route object
     * @param EventDispatcherInterface $eventDispatcher Events...
     * @return ErrorHandlerHttp
     */
    public function getErrorHandlerHttp(RouteHttpInterface $route): ErrorHandlerHttp;
}
