<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Interfaces\RouteHttpInterface;
use Core\Security\Csrf;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This middleware add headers to PSR response in api routes
 */
class WebCsrfMiddleware implements MiddlewareInterface
{

    private RouteHttpInterface $route;
    private ResponseFactoryInterface $responseFactory;
    private Csrf $csrf;
    private array $protectedMethods = [
        'POST',
        'PUT',
        'PATCH',
        'DELETE'
    ];

    public function __construct(
            Csrf $csrf,
            RouteHttpInterface $route,
            ResponseFactoryInterface $responseFactory
    )
    {
        $this->csrf = $csrf;
        $this->responseFactory = $responseFactory;
        $this->route = $route;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if ($this->route->getCsrf()) {
            if (in_array($request->getMethod(), $this->protectedMethods) && !$this->csrf->check()) {
                $response = $this->responseFactory->createResponse(403);
                $response->getBody()->write('CSRF token is invalid or missing');
                return $response;
            }
        }

        return $handler->handle($request);
    }

}
