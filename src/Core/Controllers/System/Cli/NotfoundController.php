<?php

namespace App\Core\Controllers\System\Cli;

use App\Core\Controllers\Main\Cli;

class NotfoundController extends Cli {

    public function indexAction() {
        $this->stderr("Controller or action not found");
    }

}
