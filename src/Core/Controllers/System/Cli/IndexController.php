<?php

namespace App\Core\Controllers\System\Cli;

use App\Core\Controllers\Main\Cli;

class IndexController extends Cli {

    public function indexAction() {
        $this->stdout('To run something please create controller in ' . $this->topologyFs->getBasePath() . '/Cli' . "\r\n");
    }

}
