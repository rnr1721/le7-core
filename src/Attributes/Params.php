<?php

namespace Core\Attributes;

class Params
{

    public int $allow;
    public bool $csrf;

    public function __construct(int $allow, bool $csrf)
    {
        $this->allow = $allow;
        $this->csrf = $csrf;
    }

}
