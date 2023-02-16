<?php

/* Source: https://github.com/phly/phly-event-dispatcher */

namespace le7\Core\EventDispatcher\Providers;

use Psr\EventDispatcher\ListenerProviderInterface;
use function array_keys;
use function in_array;
use function sprintf;
use function usort;

class ListenerProvider implements ListenerProviderInterface {

    private array $listeners = [];

    public function getListenersForEvent(object $event): iterable {
        $priorities = array_keys($this->listeners);
        usort($priorities, function ($a, $b) {
            return $b <=> $a;
        });

        foreach ($priorities as $priority) {
            foreach ($this->listeners[$priority] as $eventName => $listeners) {
                if ($event instanceof $eventName) {
                    foreach ($listeners as $listener) {
                        yield $listener;
                    }
                }
            }
        }
    }

    public function on(string $eventType, callable $listener, int $priority = 1): void {
        $pPriority = sprintf('%d.0', $priority);
        if (
                isset($this->listeners[$pPriority][$eventType]) && in_array($listener, $this->listeners[$pPriority][$eventType], true)
        ) {
            // Duplicate detected
            return;
        }
        $this->listeners[$pPriority][$eventType][] = $listener;
    }

}
