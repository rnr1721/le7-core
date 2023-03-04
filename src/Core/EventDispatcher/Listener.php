<?php

namespace App\Core\EventDispatcher;

use Psr\Container\ContainerInterface;

abstract class Listener {

    protected mixed $object;
    protected ContainerInterface $container;

    abstract public function trigger();

    public function __invoke($event, ContainerInterface $container): void
    {
        $this->container = $container;
        $this->object = $event->getObject();
        $this->trigger();
    }
    
}
