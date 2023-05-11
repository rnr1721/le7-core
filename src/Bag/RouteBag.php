<?php

declare(strict_types=1);

namespace Core\Bag;

use Core\Interfaces\Route;
use \Exception;

class RouteBag
{

    /**
     * Current route
     * @var Route|null
     */
    private ?Route $route = null;

    /**
     * Get current route from bag
     * @return Route
     * @throws Exception
     */
    public function getRoute(): Route
    {
        if (!$this->route instanceof Route) {
            throw new Exception('No route in bag!');
        }
        return $this->route;
    }

    /**
     * Set current route to bag
     * @param Route $route RouteHttp or RouteCli
     * @return void
     */
    public function setRoute(Route $route): void
    {
        $this->route = $route;
    }

}
