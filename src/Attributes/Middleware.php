<?php

namespace Core\Attributes;

class Middleware
{

    public array $middleware;

    public function __construct(array $middleware)
    {
        $this->middleware = $middleware;
    }

}
