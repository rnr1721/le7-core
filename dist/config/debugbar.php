<?php

use DebugBar\DataCollector\RequestDataCollector;
use Core\DebugPanel\Collectors\RouteCollector;
use Core\DebugPanel\Collectors\LocalesCollector;
use Core\DebugPanel\Collectors\PhpInfoCollector;
use Core\DebugPanel\Collectors\MemoryCollector;
use DebugBar\DataCollector\MessagesCollector;

return [
    'debugbar' => [
        'active' => true,
        'collectors' => [
            MessagesCollector::class,
            PhpInfoCollector::class,
            MemoryCollector::class,
            LocalesCollector::class,
            RouteCollector::class,
            RequestDataCollector::class
        ],
        'trusted' => [
            '127.0.0.1'
        ]
    ]
];
