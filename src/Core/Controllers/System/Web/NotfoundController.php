<?php

namespace le7\Core\Controllers\System\Web;

use le7\Core\Controllers\Main\WebPhp;

class NotfoundController extends WebPhp {

    public function indexAction() {
        $this->ulog->alert('WEB 404 not found ' . $this->request->getUri()->getQuery());
        $this->render('error404.phtml');
    }

    public function indexGetAjax() {
        $this->response->json->emitError(404);
    }

    public function indexPostAjax() {
        $this->response->json->emitError(404);
    }

    public function indexPutAjax() {
        $this->response->json->emitError(404);
    }

    public function indexDeleteAjax() {
        $this->response->json->emitError(404);
    }

}
