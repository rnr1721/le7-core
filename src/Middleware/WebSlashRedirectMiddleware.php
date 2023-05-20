<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Interfaces\RouteHttpInterface;
use Core\Interfaces\RequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This middleware redirect to page without trailing slash
 */
class WebSlashRedirectMiddleware implements MiddlewareInterface
{

    private RouteHttpInterface $route;
    private RequestInterface $request;

    public function __construct(RequestInterface $request, RouteHttpInterface $route)
    {
        $this->request = $request;
        $this->route = $route;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {   

        $uri = $request->getUri();
        $path = $uri->getPath();
        
        if ($path != $this->request->getBase() && substr($path, -1) == '/') {

            $uri = $uri->withPath(substr($path, 0, -1));

            if ($request->getMethod() == 'GET') {
                $request = $request->withAttribute('finish', true);
                $response = $handler->handle($request);
                return $response->withStatus(301)->withHeader('Location', (string) $uri);
            } else {
                return $handler->handle($request->withUri($uri))->withStatus($this->route->getResponse());
            }
        }

        return $handler->handle($request);
    }

}
