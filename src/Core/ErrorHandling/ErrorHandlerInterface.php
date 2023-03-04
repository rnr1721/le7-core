<?php

declare(strict_types=1);

namespace App\Core\ErrorHandling;

interface ErrorHandlerInterface {

public function handleError(int $errno, string $errstr, string $errfile, array $errline);
    
}
