<?php

namespace le7\Core\Controllers\System\Cli;

use le7\Core\Controllers\Main\Cli;

class NotfoundController extends Cli {

    public function indexAction() {
        $this->stderr("Controller or action not found");
    }

}
