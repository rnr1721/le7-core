<?php

declare(strict_types=1);

namespace App\Core\Instances;

use App\Core\Config\TopologyFsInterface;
use App\Core\Response\Response;
use Psr\Container\ContainerInterface;

class RouteRunnerHttp extends RouteRunner implements RouteRunnerInterface
{

    private Response $response;

    public function __construct(ContainerInterface $container, TopologyFsInterface $topologyFs, Response $response)
    {
        parent::__construct($container, $topologyFs);
        $this->response = $response;
    }

    public function run(RouteInterface $route): void
    {

        $controller = $this->getController($route->getControllerClass(), $route);

        $controllerAction = $route->getActionMethod();

        $responseCode = $this->runAction($controller, $route->getActionMethod());

        if ($responseCode === 404) {
            $this->response->setResponseCode($responseCode);
            $notfound = $route->getNotFound();
            if (!empty($notfound)) {
                $controller = $this->getController($notfound['controllerClass'], $route);
                $controllerAction = $notfound['actionMethod'];
                $this->runAction($controller, $controllerAction);
            }
        }
        if ($responseCode === 301) {
            $this->response->setResponseCode($responseCode);
        }
        $this->response->emit();
    }

}
