<?php

declare(strict_types=1);

namespace App\Core\User\UserCheck;

use App\Core\User\Passwords\PasswordsInterface;
use App\Core\Request\Request;

class UserCheckWebCookies implements UserCheckProvider {

    private PasswordsInterface $passwords;
    private Request $request;

    public function __construct(Request $request, PasswordsInterface $passwords) {
        $this->request = $request;
        $this->passwords = $passwords;
    }

    public function getToken(): string|null {
        $userToken = $this->request->getCookieParam('user_token', null);
        $userLogin = $this->request->getCookieParam('user_login', null);
        $userCredentialSession = $this->request->getCookieParam('user_credential', null);
        $userCredentialServer = $this->request->getServerParam('HTTP_USER_AGENT', null);
        if ($userToken && $userLogin && $userCredentialSession) {
            $credential = $userLogin . $userCredentialServer;
            if ($this->passwords->verify($credential, $userCredentialSession)) {
                return $userToken;
            }
        }
        return null;
    }

}
