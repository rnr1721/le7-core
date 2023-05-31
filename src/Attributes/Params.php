<?php

namespace Core\Attributes;

class Params
{

    public int $allow;
    public bool $csrf;
    public bool $active;

    public function __construct(int $allow, bool $csrf, bool $active)
    {
        $this->allow = $allow;
        $this->csrf = $csrf;
        $this->active = $active;
    }

}
