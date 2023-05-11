<?php

declare(strict_types=1);

namespace Core\Interfaces;

use Core\Interfaces\RouteHttp;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareFactory
{

    /**
     * Get request handler
     * @param RouteHttp $route Current route
     * @return RequestHandlerInterface
     */
    public function getMiddleware(RouteHttp $route): RequestHandlerInterface;
}
