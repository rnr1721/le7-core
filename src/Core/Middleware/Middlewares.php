<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Handles a server request and produces a response.
 *
 * An HTTP request handler process an HTTP request in order to produce an
 * HTTP response.
 */
class Middlewares implements RequestHandlerInterface
{

    private ?RequestHandlerInterface $defaultHandler = null;
    private $middlewares = [];

    public function __construct(RequestHandlerInterface $defaultRequestHandler)
    {
        $this->defaultHandler = $defaultRequestHandler;
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $element = $this->defaultHandler;

        $middlewares = array_reverse($this->middlewares);
        foreach ($middlewares as $middleware) {
            $element = new MiddlewareHandler($middleware, $element);
        }

        return $element->handle($request);
    }

    public function add(MiddlewareInterface $middleware)
    {
        $this->middlewares[] = $middleware;
    }

}
