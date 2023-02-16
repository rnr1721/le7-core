<?php

declare(strict_types=1);

namespace le7\Core\Instances;

interface InstanceInterface {

    public function startInstance(RouteInterface $route): RouteRunnerInterface;
}
