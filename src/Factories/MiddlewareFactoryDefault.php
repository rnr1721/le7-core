<?php

declare(strict_types=1);

namespace Core\Factories;

use Core\Interfaces\ConfigInterface;
use Core\Interfaces\RouteHttpInterface;
use Core\Interfaces\MiddlewareFactoryInterface;
use Core\Middleware\ControllerRunMiddleware;
use Core\RequestHandler\DefaultHandler;
use Core\RequestHandler\MiddlewareDispatcherDefault;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This is a class that implements the MiddlewareFactory interface.
 * Its purpose is to create and return a PSR-15 middleware stack based on the
 * given HTTP route. The middleware stack is essentially a series of middleware
 * functions that handle the incoming request and prepare it for the final
 * request handler.
 */
class MiddlewareFactoryDefault implements MiddlewareFactoryInterface
{

    private ConfigInterface $config;
    private ContainerInterface $container;

    public function __construct(
            ConfigInterface $config,
            ContainerInterface $container
    )
    {
        $this->config = $config;
        $this->container = $container;
    }

    /**
     * The getMiddleware method takes a RouteHttp instance as a parameter and
     * returns a RequestHandlerInterface instance. It creates an instance of
     * MiddlewareDispatcherDefault and adds the middleware classes retrieved
     * from the RouteHttp instance and the global middleware classes retrieved
     * from the Config instance to it. Finally, it returns the
     * MiddlewareDispatcherDefault instance as the request handler for the
     * given HTTP route.
     * @param RouteHttpInterface $route
     * @return RequestHandlerInterface
     */
    public function getMiddleware(RouteHttpInterface $route): RequestHandlerInterface
    {

        $defaultHandler = $this->container->get(DefaultHandler::class);
        $requestHandler = new MiddlewareDispatcherDefault($defaultHandler);

        $key = 'middleware.' . $route->getType();
        $globalMiddlewares = $this->config->array($key) ?? [];

        $runner = $this->config->string('runner', ControllerRunMiddleware::class) ?? '';
        foreach ($globalMiddlewares as $classGlobal) {
            if ($classGlobal === $runner) {
                foreach ($route->getMiddleware() as $classLocal) {
                    $item = $this->container->get($classLocal);
                    $requestHandler->add($item);
                }
            }
            $item = $this->container->get($classGlobal);
            $requestHandler->add($item);
        }

        $requestHandler->setReverse(true);

        return $requestHandler;
    }

}
