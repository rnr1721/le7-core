<?php

namespace le7\Core\Controllers\System\Cli;

use le7\Core\Controllers\Main\Cli;

class IndexController extends Cli {

    public function indexAction() {
        $this->stdout('To run something please create controller in ' . $this->topologyFs->getBasePath() . '/Cli' . "\r\n");
    }

}
