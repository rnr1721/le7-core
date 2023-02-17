<?php

namespace le7\Core\Instances;

interface RouteRunnerInterface {

    public function run(RouteInterface $route): void;
}
