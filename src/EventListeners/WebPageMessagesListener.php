<?php

declare(strict_types=1);

namespace Core\EventListeners;

use Core\Interfaces\MessageCollection;
use Core\EventDispatcher\Listener;

class WebPageMessagesListener extends Listener
{

    private MessageCollection $messages;

    public function __construct(
            MessageCollection $messages,
    )
    {
        $this->messages = $messages;
    }

    public function trigger(): void
    {
        $this->event->setVar('messages', $this->messages->getAll());
    }

}
