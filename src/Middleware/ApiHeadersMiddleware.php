<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Interfaces\ConfigInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This middleware add headers to PSR response in api routes
 */
class ApiHeadersMiddleware implements MiddlewareInterface
{

    private ConfigInterface $config;
    private array $defaultHeaders = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE, HEAD',
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Allow-Headers' => 'Content-Type, Origin, Authorizations, User-Agent, Host, Authorization, Content-Length, Accept, X-Requested-With, X-Auth-Token, Content-Language, Source'
    ];

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        
        $headers = $this->config->array('headers.api') ?? $this->defaultHeaders;
        
        $response = $handler->handle($request);

        if ($headers) {
            foreach ($headers as $key => $value) {
                $response = $response->withHeader($key, $value);
            }
        }

        return $response;
    }

}
