<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\Instances\RouteInterface;
use le7\Core\Traits\ConsoleTrait;

class Cli extends Main {

    public RouteInterface $route;

    use ConsoleTrait;
    
}
