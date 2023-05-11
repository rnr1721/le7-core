<?php

declare(strict_types=1);

namespace Core;

use Core\Routing\RouteBuilderCli;
use Core\Bag\RouteBag;
use Core\Console\CliHandler;

class InitCli
{

    use InitTrait;

    public function run(): void
    {
        $container = $this->getContainer();
        $routeBuilder = $container->get(RouteBuilderCli::class);
        $route = $routeBuilder->getCurrentRoute();
        /** @var RouteBag $routeBag */
        $routeBag = $container->get(RouteBag::class);
        $routeBag->setRoute($route);
        $handler = $container->get(CliHandler::class);
        $handler->handle($route);
    }

}
