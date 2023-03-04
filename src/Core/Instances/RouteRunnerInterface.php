<?php

namespace App\Core\Instances;

interface RouteRunnerInterface {

    public function run(RouteInterface $route): void;
}
