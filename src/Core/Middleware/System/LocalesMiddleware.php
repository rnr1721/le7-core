<?php

declare(strict_types=1);

namespace le7\Core\Middleware\System;

use le7\Core\Locales\LocalesInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LocalesMiddleware implements MiddlewareInterface
{

    private LocalesInterface $locales;

    public function __construct(LocalesInterface $Locales)
    {
        $this->locales = $Locales;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        /** @var \le7\Core\Instances\RouteHttpInterface $route */
        $route = $request->getAttribute('route');

        $this->locales->setLocale($route->getLanguage());

        return $response;
    }

}
