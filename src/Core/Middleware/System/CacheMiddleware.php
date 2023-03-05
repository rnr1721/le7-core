<?php

declare(strict_types=1);

namespace App\Core\Middleware\System;

use App\Core\Response\Response;
use Psr\SimpleCache\CacheInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CacheMiddleware implements MiddlewareInterface
{

    private Response $response;
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache, Response $response)
    {
        $this->cache = $cache;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        /** @var \App\Core\Instances\RouteHttpInterface $route */
        $route = $request->getAttribute('route');

        $cacheName = $route->getType() . '_' . md5((string) $request->getUri());

        $cacheItem = $this->cache->get($cacheName);
        if ($cacheItem && $route->getResponse() === 200 && $route->getMethod() === 'GET') {
            $this->response->responsePsr7 = $response;
            $this->response->setResponseCode(200);
            $this->response->setBody($cacheItem);
            $this->response->emit();
        }

        return $response;
    }

}
