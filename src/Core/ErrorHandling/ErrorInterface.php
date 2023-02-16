<?php

declare(strict_types=1);

namespace le7\Core\ErrorHandling;

use Throwable;

interface ErrorInterface
{
    public function show(Throwable|null $exception, array $errors):void;
}
