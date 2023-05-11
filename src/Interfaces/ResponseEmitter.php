<?php

declare(strict_types=1);

namespace Core\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface ResponseEmitter
{

    /**
     * Emit the ResponseInterface object
     * @param ResponseInterface $response
     * @return void
     */
    public function emit(ResponseInterface $response): void;
}
