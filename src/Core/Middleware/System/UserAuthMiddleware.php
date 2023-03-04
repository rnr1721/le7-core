<?php

declare(strict_types=1);

namespace App\Core\Middleware\System;

use App\Core\Request\Request;
use App\Core\User\UserManager;
use App\Core\Config\ConfigInterface;
use App\Core\Database\DbManager;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserAuthMiddleware implements MiddlewareInterface
{

    private Request $requestSystem;
    private ConfigInterface $config;
    private UserManager $userManager;
    private DbManager $dbFactory;

    public function __construct(Request $request, ConfigInterface $config, DbManager $dbFactory, UserManager $userManager)
    {
        $this->config = $config;
        $this->dbFactory = $dbFactory;
        $this->userManager = $userManager;
        $this->requestSystem = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        /** @var \App\Core\Instances\RouteHttpInterface $route */
        $route = $request->getAttribute('route');

        if ($this->config->getUserManagementOn()) {
            $userIdentity = match ($route->getType()) {
                'web' => $this->userManager->getUserWeb(),
                'api' => $this->userManager->getUserApi()
            };
            $user = $userIdentity->getUser($this->dbFactory->getDbConn());
        }
        
        $this->requestSystem->setAttribute('user', $user);
        
        return $response;
    }

}
