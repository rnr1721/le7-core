<?php

declare(strict_types=1);

namespace Core\Interfaces;

use Core\ErrorHandler\ErrorHandlerCli;
use Core\ErrorHandler\ErrorHandlerHttp;
use Core\Interfaces\RouteHttp;

interface ErrorHandlerFactory
{

    /**
     * Get configured Error Handler for command line
     * @return ErrorHandlerCli
     */
    public function getErrorHandlerCli(): ErrorHandlerCli;

    /**
     * Get configured Error Handler for http
     * @param RouteHttp $route Route object
     * @param EventDispatcherInterface $eventDispatcher Events...
     * @return ErrorHandlerHttp
     */
    public function getErrorHandlerHttp(RouteHttp $route): ErrorHandlerHttp;
}
