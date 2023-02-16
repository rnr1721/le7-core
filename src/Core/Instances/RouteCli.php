<?php

declare(strict_types=1);

namespace le7\Core\Instances;

class RouteCli extends Route implements RouteCliInterface {
    
    public function getOptions() : array {
        return $this->route['options'];
    }
    
}
