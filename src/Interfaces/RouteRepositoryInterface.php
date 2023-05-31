<?php

declare(strict_types=1);

namespace Core\Interfaces;

interface RouteRepositoryInterface
{

    public function setRouteCollection(array $routes): self;

    public function getRoutes(): array;
}
