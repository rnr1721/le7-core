<?php

declare(strict_types=1);

namespace App\Core\ErrorHandling;

use Throwable;

interface ErrorInterface
{
    public function show(Throwable|null $exception, array $errors):void;
}
