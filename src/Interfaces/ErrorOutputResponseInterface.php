<?php

declare(strict_types=1);

namespace Core\Interfaces;

use Psr\Http\Message\ResponseInterface;
use \Throwable;

interface ErrorOutputResponseInterface
{

    /**
     * Show error
     * @param Throwable|null $exception
     * @param array $errors
     * @return ResponseInterface
     */
    public function get(Throwable|null $exception, array $errors): ResponseInterface;
}
