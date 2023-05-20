<?php

declare(strict_types=1);

namespace Core\Interfaces;

use Core\Interfaces\RouteHttpInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface MiddlewareFactoryInterface
{

    /**
     * Get request handler
     * @param RouteHttpInterface $route Current route
     * @return RequestHandlerInterface
     */
    public function getMiddleware(RouteHttpInterface $route): RequestHandlerInterface;
}
