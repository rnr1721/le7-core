<?php

namespace le7\Core\Controllers\System\Web;

use le7\Core\Controllers\Main\WebPhp;

class IndexController extends WebPhp {

    public function indexAction() {
        $this->render('index_placeholder.phtml');
    }

    public function indexGetAjax() {
        $this->response->json->emitSuccess();
    }

    public function indexPostAjax() {
        $this->response->json->emitSuccess();
    }

    public function indexPutAjax() {
        $this->response->json->emitSuccess();
    }

    public function indexDeleteAjax() {
        $this->response->json->emitSuccess();
    }

}
