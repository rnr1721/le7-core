<?php

declare(strict_types=1);

namespace le7\Core\User\UserCheck;

use le7\Core\Request\Request;

class UserCheckWebSession implements UserCheckProvider {

    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function getToken(): string|null {
        $userToken = $_SESSION['user_token'] ?? null;
        $userCredentialSession = $_SESSION['user_credential'] ?? null;
        $userCredentialServer = $this->request->getServerParam('HTTP_USER_AGENT', null);
        if ($userToken && $userCredentialSession) {
            $credential = $userToken . $userCredentialServer;
            if (password_verify($credential, $userCredentialSession)) {
                return $userToken;
            }
        }
        return null;
    }

}
