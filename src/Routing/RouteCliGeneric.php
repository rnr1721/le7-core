<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Interfaces\RouteCli;

class RouteCliGeneric extends RouteGeneric implements RouteCli
{

    public function getOptions(): array
    {
        return $this->getArray('options');
    }

    public function getParam(string $paramName, string|int|bool|null $default = null): string|int|bool|null
    {
        if (array_key_exists($paramName, $this->getArray('params'))) {
            return $this->route['params'][$paramName];
        }
        return $default;
    }

}
