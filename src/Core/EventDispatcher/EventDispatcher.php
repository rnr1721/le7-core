<?php

namespace App\Core\EventDispatcher;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface {

    private ContainerInterface $container;
    private ListenerProviderInterface $provider;

    public function __construct(ListenerProviderInterface $provider, ContainerInterface $container) {
        $this->provider = $provider;
        $this->container = $container;
    }

    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object $event
     *   The object to process.
     *
     * @return object
     *   The Event that was passed, now modified by listeners.
     */
    public function dispatch(object $event): object {

        $canStop = $event instanceof StoppableEventInterface;

        if ($canStop && $event->isPropagationStopped()) {
            return $event;
        }

        foreach ($this->provider->getListenersForEvent($event) as $listener) {
            $listener($event,$this->container);
            if ($canStop && $event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }

}
