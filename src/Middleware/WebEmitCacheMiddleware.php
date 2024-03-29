<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Interfaces\ViewInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This middleware try to render cached content if it exists
 */
class WebEmitCacheMiddleware implements MiddlewareInterface
{

    private ViewInterface $view;

    public function __construct(ViewInterface $view)
    {
        $this->view = $view;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        
        $pCachedResponse = $this->view->renderFromCache();

        if ($pCachedResponse) {
            $request = $request->withAttribute('finish', true);
            return $pCachedResponse;
        }
        
        return $handler->handle($request);
    }

}
