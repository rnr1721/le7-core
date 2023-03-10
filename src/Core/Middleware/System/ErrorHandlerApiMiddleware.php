<?php

declare(strict_types=1);

namespace App\Core\Middleware\System;

use App\Core\ErrorHandling\ErrorHandlerHttpFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorHandlerApiMiddleware implements MiddlewareInterface
{

    private ErrorHandlerHttpFactory $errorHandlerFactory;

    public function __construct(ErrorHandlerHttpFactory $errorHandlerFactory)
    {
        $this->errorHandlerFactory = $errorHandlerFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $this->errorHandlerFactory->getErrorHandlerJson();

        return $response;
    }

}
