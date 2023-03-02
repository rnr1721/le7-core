<?php

declare(strict_types=1);

namespace le7\Core\Middleware\System;

use le7\Core\Request\Request;
use le7\Core\User\UserManager;
use le7\Core\Config\ConfigInterface;
use le7\Core\Database\DatabaseFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserAuthMiddleware implements MiddlewareInterface
{

    private Request $requestSystem;
    private ConfigInterface $config;
    private UserManager $userManager;
    private DatabaseFactory $dbFactory;

    public function __construct(Request $request, ConfigInterface $config, DatabaseFactory $dbFactory, UserManager $userManager)
    {
        $this->config = $config;
        $this->dbFactory = $dbFactory;
        $this->userManager = $userManager;
        $this->requestSystem = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        /** @var \le7\Core\Instances\RouteHttpInterface $route */
        $route = $request->getAttribute('route');

        if ($this->config->getUserManagementOn()) {
            $userIdentity = match ($route->getType()) {
                'web' => $this->userManager->getUserWeb(),
                'api' => $this->userManager->getUserApi()
            };
            $user = $userIdentity->getUser($this->dbFactory->getDatabaseConnection());
        }
        
        $this->requestSystem->setAttribute('user', $user);
        
        return $response;
    }

}
