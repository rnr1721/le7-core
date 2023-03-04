<?php

declare(strict_types=1);

namespace App\Core\Instances;

class Route implements RouteInterface
{

    protected array $route = array();

    public function __construct(array $route)
    {
        if (!empty($route['notfound'])) {
            $this->routeNotfound = $route['notfound'];
            unset($route['notfound']);
        }
        $this->route = $route;
    }

    public function getType(): string
    {
        return $this->route['type'];
    }

    public function getCase(): string
    {
        return $this->route['case'];
    }

    public function getController(): string
    {
        return $this->route['controller'];
    }

    public function getAction(): string
    {
        return $this->route['action'];
    }

    public function getControllerClass(): string
    {
        return $this->route['controllerClass'];
    }

    public function getActionMethod(): string
    {
        return $this->route['actionMethod'];
    }

    public function getParams(): array
    {
        return $this->route['params'];
    }

    public function getParam(int $param, string|int|bool|null $default): string|int|bool|null
    {
        if (array_key_exists($param, $this->route['params'])) {
            return $this->route['params'][$param];
        }
        return $default;
    }

    public function getLanguage(): string
    {
        return $this->route['language'];
    }

    public function exportArray(): array
    {
        return $this->route;
    }

    public function exportObject(): object
    {
        return (object) $this->route;
    }

    public function getNotFound(): array
    {
        return $this->routeNotfound;
    }

}
