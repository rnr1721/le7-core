<?php

declare(strict_types=1);

namespace Core\Interfaces;

use Psr\Container\ContainerInterface;

interface ContainerFactory
{

    /**
     * Get configured PSR container
     * @param bool $isProduction
     * @return ContainerInterface
     */
    public function getContainer(bool $isProduction): ContainerInterface;
}
