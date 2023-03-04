<?php

declare(strict_types=1);

namespace App\Core\Instances;

use App\Core\Traits\ConsoleTrait;

class RouteRunnerCli extends RouteRunner implements RouteRunnerInterface {

    use ConsoleTrait;

    public function run(RouteInterface $route): void {
        
        $controller = $this->getController($route->getControllerClass(), $route);
        
        //$controllerAction = $route->getActionMethod();

        $responseCode = $this->runAction($controller, $route->getActionMethod());

        $this->stdout($responseCode . PHP_EOL);
    }

}
