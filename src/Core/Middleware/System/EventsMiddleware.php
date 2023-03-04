<?php

declare(strict_types=1);

namespace App\Core\Middleware\System;

use App\Core\EventDispatcher\EventInvoker;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EventsMiddleware implements MiddlewareInterface
{

    private EventInvoker $eventInvoker;

    public function __construct(EventInvoker $eventInvoker)
    {
        $this->eventInvoker = $eventInvoker;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $this->eventInvoker->processEvents();
        
        return $response;
    }

}
