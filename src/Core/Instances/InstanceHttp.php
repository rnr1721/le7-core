<?php

declare(strict_types=1);

namespace App\Core\Instances;

use App\Core\Middleware\MiddlewaresFactory;
use App\Core\Instances\RouteRunnerHttp;
use App\Core\Request\Request;
use App\Core\Response\Response;

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

        $middlewares = $this->middlewaresFactory->getMiddlewares($route);

        $this->request->setAttribute('route', $route);
        $response = $middlewares->handle($this->request->getRequest());
        $this->response->responsePsr7 = $response;

        return $this->routeRunner;
    }

}
