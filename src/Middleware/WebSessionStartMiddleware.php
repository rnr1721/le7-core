<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Interfaces\SessionInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This middleware try to start session
 */
class WebSessionStartMiddleware implements MiddlewareInterface
{
    
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        
        $this->session->start();

        return $handler->handle($request);
    }

}
