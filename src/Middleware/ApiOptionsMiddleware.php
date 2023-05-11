<?php

declare(strict_types=1);

namespace Core\Middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiOptionsMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if ($request->getMethod() === 'OPTIONS') {
            $request = $request->withAttribute('finish', true);
        }

        return $handler->handle($request);
    }

}
