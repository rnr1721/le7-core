<?php

namespace App\Core\Controllers\System\Web;

use App\Core\Controllers\Main\Web;

class PhpinfoController extends Web {
    
    public function indexAction() {
        if ($this->config->getIsProduction()) {
            return 404;
        }
        ob_start();
        phpinfo();
        $result = ob_get_clean();
        ob_flush();
        $this->response->html->emit($result);
    }
    
}
