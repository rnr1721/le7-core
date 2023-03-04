<?php

declare(strict_types=1);

namespace App\Core\Middleware\System;

use App\Core\Locales\LocalesInterface;
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

        /** @var \App\Core\Instances\RouteHttpInterface $route */
        $route = $request->getAttribute('route');

        $this->locales->setLocale($route->getLanguage());

        return $response;
    }

}
