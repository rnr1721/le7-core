<?php

declare(strict_types=1);

namespace Core\Middleware;

use Core\Interfaces\MiddlewareHandlerInterface;
use Core\Interfaces\RouteHttpInterface;
use Core\Routing\RunnerTrait;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use \RuntimeException;

class ControllerRunMiddleware implements MiddlewareInterface
{

    use RunnerTrait;

    private RouteHttpInterface $route;
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container, RouteHttpInterface $route)
    {
        $this->container = $container;
        $this->route = $route;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if ($request->getAttribute('finish', false) === false) {

            // Get controller from container
            $controller = $this->container->get($this->route->getControllerClass());

            // Run action in controller
            $response = $this->runAction($controller, $this->route->getActionMethod());

            // Return response
            if (!$response instanceof ResponseInterface) {
                throw new RuntimeException("You must return ResponseInterface from controller");
            }

            //$code = $response->getStatusCode();
            //if ($code === 301 || $code === 302) {
            //    return $response;
            //}

            /** @var MiddlewareHandlerInterface $handler */
            if (method_exists($handler, 'withResponse')) {
                $handler = $handler->withResponse($response);
            }
        }

        return $handler->handle($request);
    }

}
