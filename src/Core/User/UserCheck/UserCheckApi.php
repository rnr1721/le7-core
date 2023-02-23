<?php

declare(strict_types=1);

namespace le7\Core\User\UserCheck;

use le7\Core\Request\Request;

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
