<?php

declare(strict_types=1);

namespace App\Core\Instances;

interface RouteCliInterface extends RouteInterface {

    /**
     * Get options of route from console
     * @return array
     */
    public function getOptions(): array;
}
