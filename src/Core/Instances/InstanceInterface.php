<?php

declare(strict_types=1);

namespace App\Core\Instances;

interface InstanceInterface {

    public function startInstance(RouteInterface $route): RouteRunnerInterface;
}
