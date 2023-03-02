<?php

declare(strict_types=1);

namespace le7\Core\Middleware\System;

use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Php;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CookiesSetupMiddleware implements MiddlewareInterface
{
    
    private TopologyFsInterface $topologyFs;
    private ConfigInterface $config;
    private Php $php;
    
    public function __construct(ConfigInterface $config, TopologyFsInterface $topologyFs, Php $php)
    {
        $this->topologyFs = $topologyFs;
        $this->config = $config;
        $this->php = $php;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        
        $this->php->setHttpOnly();
        $this->php->setSessionCookieSecure();
        $this->php->setSessionCookieSameSite($this->config->getSessionCookieSamesite());
        $this->php->setSessionPath($this->topologyFs->getPhpSessionsPath());

        session_start();
        
        return $response;
    }

}
