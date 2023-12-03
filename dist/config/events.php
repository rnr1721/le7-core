<?php

use Core\Events\BeforeRenderEvent;
use Core\EventListeners\DebugBarListener;
use Core\EventListeners\WebPageMessagesListener;

return [
    'events' => [
        // Set messages list to WebPage object for templates
        [
            BeforeRenderEvent::class,
            WebPageMessagesListener::class
        ],
        // Plug in debugbar if can start
        [
            BeforeRenderEvent::class,
            DebugBarListener::class
        ]
    ]
];
