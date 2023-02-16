<?php

namespace le7\Core\EventDispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

class Event implements StoppableEventInterface {

    private $object;
    private $propagationStopped = false;

    public function __construct(object|null $object) {
        $this->object = $object;
    }

    public function isPropagationStopped(): bool {
        return $this->propagationStopped;
    }

    public function stopPropagation(): void {
        $this->propagationStopped = true;
    }

    public function getObject(): object|null {
        return $this->object;
    }

}
