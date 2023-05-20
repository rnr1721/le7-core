<?php

declare(strict_types=1);

namespace Core\Bag;

use Core\Interfaces\RouteInterface;
use \Exception;

class RouteBag
{

    /**
     * Current route
     * @var RouteInterface|null
     */
    private ?RouteInterface $route = null;

    /**
     * Get current route from bag
     * @return RouteInterface
     * @throws Exception
     */
    public function getRoute(): RouteInterface
    {
        if (!$this->route instanceof RouteInterface) {
            throw new Exception('No route in bag!');
        }
        return $this->route;
    }

    /**
     * Set current route to bag
     * @param RouteInterface $route RouteHttp or RouteCli
     * @return void
     */
    public function setRoute(RouteInterface $route): void
    {
        $this->route = $route;
    }

}
