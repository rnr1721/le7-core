<?php

declare(strict_types=1);

namespace le7\Core\Middleware\System;

use le7\Core\Response\Response;
use le7\Core\Config\ConfigInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ApiMethodMiddleware implements MiddlewareInterface
{

    private Response $response;
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config, Response $response)
    {
        $this->config = $config;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        /** @var \le7\Core\Instances\RouteHttpInterface $route */
        $route = $request->getAttribute('route');

        $responseWithHeaders = $this->getResponseWithHeaders($response);

        if ($route->getMethod() === 'OPTIONS') {
            $this->response->responsePsr7 = $responseWithHeaders;
            $this->response->setResponseCode(200);
            $this->response->emit();
        }

        return $responseWithHeaders->withStatus($route->getResponse());
    }

    private function getResponseWithHeaders(ResponseInterface $response): ResponseInterface
    {
        return $response->withHeader('Access-Control-Allow-Origin', '*')
                        ->withHeader('Access-Control-Allow-Methods', $this->config->getApiAllowedMethods())
                        ->withHeader('Access-Control-Allow-Credentials', 'true')
                        ->withHeader('Access-Control-Allow-Headers', $this->config->getApiAllowedHeaders());
    }

}
