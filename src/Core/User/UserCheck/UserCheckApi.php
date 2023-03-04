<?php

declare(strict_types=1);

namespace App\Core\User\UserCheck;

use App\Core\Request\Request;

class UserCheckApi implements UserCheckProvider {

    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function getToken(): string|null {
        $res = $this->request->getHeaderLine('X-Auth-Token');
        if ($res === '') {
            return null;
        }
        return $res;
    }

}
