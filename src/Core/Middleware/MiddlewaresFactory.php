<?php

namespace App\Core\Middleware;

use App\Core\Instances\RouteHttpInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Container\ContainerInterface;
use App\Core\Config\TopologyFsInterface;

class MiddlewaresFactory
{

    private ResponseFactoryInterface $responseFactory;
    private TopologyFsInterface $topologyFs;
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container, TopologyFsInterface $topologyFs, ResponseFactoryInterface $responseFactory)
    {
        $this->container = $container;
        $this->topologyFs = $topologyFs;
        $this->responseFactory = $responseFactory;
    }

    public function getMiddlewares(RouteHttpInterface $route): Middlewares
    {
        $defaultHandler = new DefaultHandler($this->responseFactory);

        $middlewares = new Middlewares($defaultHandler);

        $middlewareFile = $this->topologyFs->getConfigUserPath() . DIRECTORY_SEPARATOR . 'middleware.php';

        if (file_exists($middlewareFile)) {
            $middlewareArray = require($middlewareFile);
        } else {
            $middlewareArray = [];
        }

        foreach ($route->getMiddleware() as $class) {
            $item = $this->container->get($class);
            $middlewares->add($item);
        }
        
        foreach ($middlewareArray[$route->getType()] as $class) {
            $item = $this->container->get($class);
            $middlewares->add($item);
        }
        
        return $middlewares;
    }

}
