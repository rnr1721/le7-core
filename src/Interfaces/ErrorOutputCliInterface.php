<?php

declare(strict_types=1);

namespace Core\Interfaces;

use \Throwable;

interface ErrorOutputCliInterface
{

    /**
     * Show error
     * @param Throwable|null $exception
     * @param array $errors
     */
    public function show(Throwable|null $exception, array $errors): void;
}
