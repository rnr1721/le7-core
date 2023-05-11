<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Interfaces\RouteHttp;
use Core\Interfaces\Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This middleware redirect to page without trailing slash
 */
class WebSlashRedirectMiddleware implements MiddlewareInterface
{

    private RouteHttp $route;
    private Request $request;

    public function __construct(Request $request, RouteHttp $route)
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
