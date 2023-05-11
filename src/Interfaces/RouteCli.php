<?php

declare(strict_types=1);

namespace Core\Interfaces;

interface RouteCli extends Route {

    /**
     * Get options of route from console
     * @return array
     */
    public function getOptions(): array;
    
    /**
     * Return one URL parameter by name or default value
     * @param string $paramName Name of the parameter
     * @param string|int|bool|null $default Default value if parameter not exist
     * @return string|int|bool|null
     */
    public function getParam(string $paramName, string|int|bool|null $default = null): string|int|bool|null;

}
