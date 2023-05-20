<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Interfaces\MessageCollectionFlashInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This middleware try put flash messages if it exists to session or to cookies
 */
class WebPutMessagesMiddleware implements MiddlewareInterface
{

    private MessageCollectionFlashInterface $messagesFlash;

    public function __construct(MessageCollectionFlashInterface $messagesFlash)
    {
        $this->messagesFlash = $messagesFlash;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $this->messagesFlash->putToDestination();
        
        return $handler->handle($request);
    }

}
