<?php

declare(strict_types=1);

namespace App\Core\Controllers\Main;

use App\Core\Instances\RouteInterface;
use App\Core\Traits\ConsoleTrait;

class Cli extends Main {

    public RouteInterface $route;

    use ConsoleTrait;
    
}
