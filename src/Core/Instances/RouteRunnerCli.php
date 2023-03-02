<?php

declare(strict_types=1);

namespace le7\Core\Instances;

use le7\Core\Traits\ConsoleTrait;

class RouteRunnerCli extends RouteRunner implements RouteRunnerInterface {

    use ConsoleTrait;

    public function run(RouteInterface $route): void {
        
        $controller = $this->getController($route->getControllerClass(), $route);
        
        //$controllerAction = $route->getActionMethod();

        $params = [];
        if (method_exists($controller, 'trigger')) {
            $params = $controller->trigger();
        }

        $responseCode = $this->runAction($controller, $route->getActionMethod());

        $this->stdout($responseCode . PHP_EOL);
    }

}
