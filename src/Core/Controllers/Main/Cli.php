<?php

declare(strict_types=1);

namespace App\Core\Controllers\Main;

use App\Core\Instances\RouteInterface;
use App\Core\Traits\ConsoleTrait;

/**
 * Default controller for CLI requests
 * All cli controllers must extends from it
 */
class Cli extends Main {

    /**
     * Current route
     * @var RouteInterface
     */
    public RouteInterface $route;

    /**
     * Trait for console output and input
     */
    use ConsoleTrait;
    
}
