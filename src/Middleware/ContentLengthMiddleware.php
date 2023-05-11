<?php

declare(strict_types=1);

namespace Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ContentLengthMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $response = $handler->handle($request);

        $size = $response->getBody()->getSize();

        if ($size === null) {
            return $response;
        }
        return $response->withHeader('Content-Length', (string) $size);
    }

}
