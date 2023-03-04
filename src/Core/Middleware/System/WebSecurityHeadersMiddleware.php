<?php

declare(strict_types=1);

namespace App\Core\Middleware\System;

use App\Core\Config\ConfigInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class WebSecurityHeadersMiddleware implements MiddlewareInterface
{

    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        
        return $response->
                withHeader('Strict-Transport-Security', $this->config->getHeaderStrictTransportSecurity())
                ->withHeader('Content-Security-Policy', $this->config->getHeaderContentSecurityPolicy())
                ->withHeader('Referrer-Policy', $this->config->getHeaderReferrerPolicy())
                ->withHeader('X-Content-Type-Options', $this->config->getHeaderXcontentTypeOptions())
                ->withHeader('X-Frame-Options', $this->config->getHeaderXframeOptions())
                ->withHeader('X-XSS-Protection', $this->config->getHeaderXxssProtection());
    }

}
