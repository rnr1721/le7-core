<?php

declare(strict_types=1);

namespace le7\Core\ErrorHandling;

interface ErrorHandlerInterface {

public function handleError(int $errno, string $errstr, string $errfile, array $errline);
    
}
