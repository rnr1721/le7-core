<?php

namespace Core\Controller\Console;

class Notfound extends BaseController
{

    public function indexAction(): void
    {
        $this->stderr("Controller or action not found");
    }

}
