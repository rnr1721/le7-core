<?php

namespace le7\Core\Controllers\System\Web;

class PhpinfoController extends \le7\Core\Controllers\Main\Web {
    
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
