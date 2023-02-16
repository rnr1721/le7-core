<?php

namespace le7\Core\Instances;

interface RouteRunnerInterface {

    public function run(object $controller, RouteInterface $route): void;
}
