<?php

declare(strict_types=1);

namespace le7\Core\Instances;

use le7\Core\Middleware\MiddlewaresFactory;
use le7\Core\Instances\RouteRunnerHttp;
use le7\Core\Request\Request;
use le7\Core\Response\Response;

class InstanceHttp implements InstanceInterface
{

    protected MiddlewaresFactory $middlewaresFactory;
    protected RouteRunnerHttp $routeRunner;
    protected Request $request;
    protected Response $response;

    public function __construct(
            Request $request,
            Response $response,
            RouteRunnerHttp $routeRunner,
            MiddlewaresFactory $middlewaresFactory
    )
    {
        $this->request = $request;
        $this->routeRunner = $routeRunner;
        $this->middlewaresFactory = $middlewaresFactory;
        $this->response = $response;
    }

    public function startInstance(RouteInterface $route): RouteRunnerInterface
    {

        $middlewares = $this->middlewaresFactory->getMiddlewares($route->getType());

        $this->request->setAttribute('route', $route);
        $response = $middlewares->handle($this->request->getRequest());
        $this->response->responsePsr7 = $response;

        return $this->routeRunner;
    }

}
