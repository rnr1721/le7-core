<?php

namespace App\Core\Controllers\System\Api;

use App\Core\Controllers\Main\Api;

class NotfoundController extends Api {

    public function indexGetAction() {
        $this->response->json->emitError(404);
    }

    public function indexPostAction() {
        $this->response->json->emitError(404);
    }

    public function indexPutAction() {
        $this->response->json->emitError(404);
    }

    public function indexDeleteAction() {
        $this->response->json->emitError(404);
    }

}
