<?php

namespace App\Core\Log;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

use Stringable;

class Logger extends AbstractLogger implements LoggerInterface
{

    private array $broadcast = array();

    public function log($level, Stringable | string $message, array $context = []): void
    {
        foreach ($this->broadcast as $route)
        {
            if (!$route instanceof LoggerRoute)
            {
                continue;
            }
            if (!$route->isEnable())
            {
                continue;
            }
            $route->log($level, $message, $context);
        }
    }

    public function addBroadcast(LoggerRoute $route) {
        $this->broadcast[] = $route;
    }

}
