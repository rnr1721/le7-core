<?php

declare(strict_types=1);

namespace Core\EventListeners;

use Core\Interfaces\MessageCollectionInterface;
use Core\EventDispatcher\Listener;

class WebPageMessagesListener extends Listener
{

    private MessageCollectionInterface $messages;

    public function __construct(
            MessageCollectionInterface $messages,
    )
    {
        $this->messages = $messages;
    }

    public function trigger(): void
    {
        $this->event->setVar('messages', $this->messages->getAll());
    }

}
