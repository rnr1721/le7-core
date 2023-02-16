<?php

declare(strict_types=1);

namespace le7\Core\Instances;

use le7\Core\Response\Response;

class RouteRunnerHttp implements RouteRunnerInterface {

    private Response $response;

    public function __construct(Response $response) {
        $this->response = $response;
    }

    public function run(object $controller, RouteInterface $route):void {

        $controllerAction = $route->getActionMethod();

        $params = [];
        if (method_exists($controller, 'trigger')) {
            $params = $controller->trigger();
        }

        $responseCode = $controller->{$controllerAction}($params);

        if ($responseCode === 404) {
            $this->response->setResponseCode($responseCode);
            $notfound = $route->getNotFound();
            if (!empty($notfound)) {
                $controller = $this->getController($notfound['controllerClass'], $route);
                $controllerAction = $notfound['actionMethod'];
                $controller->{$controllerAction}();
            }
        }
        if ($responseCode === 301) {
            $this->response->setResponseCode($responseCode);
        }
        $this->response->emit();
    }

}
