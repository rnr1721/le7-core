<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Interfaces\MessageCollectionInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This middleware try to get messages from session or from cookies if exists
 */
class WebHandleMessagesMiddleware implements MiddlewareInterface
{

    private MessageCollectionInterface $messages;

    public function __construct(MessageCollectionInterface $messages)
    {
        $this->messages = $messages;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $this->messages->loadMessages();

        return $handler->handle($request);
    }

}
