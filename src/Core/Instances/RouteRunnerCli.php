<?php

declare(strict_types=1);

namespace le7\Core\Instances;

use le7\Core\Traits\ConsoleTrait;

class RouteRunnerCli implements RouteRunnerInterface {

    use ConsoleTrait;

    public function run(object $controller, RouteInterface $route): void {
        $controllerAction = $route->getActionMethod();

        $params = [];
        if (method_exists($controller, 'trigger')) {
            $params = $controller->trigger();
        }

        $responseCode = $controller->{$controllerAction}($params);

        $this->stdout($responseCode . PHP_EOL);
    }

}
