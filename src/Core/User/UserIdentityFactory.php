<?php

declare(strict_types=1);

namespace le7\Core\User;

use le7\Core\User\Verification\VerificationCodeDb;
use le7\Core\User\Verification\VerificationCodeInterface;
use le7\Core\User\UserIdentity;
use le7\Core\User\UserLogin;
use le7\Core\User\UserCheck;
use le7\Core\User\UserCheck\UserCheckApi;
use le7\Core\User\UserCheck\UserCheckWebSession;
use le7\Core\User\UserCheck\UserCheckWebCookies;
use le7\Core\User\UserLogin\UserLoginApi;
use le7\Core\User\UserLogin\UserLoginWebSession;
use le7\Core\User\UserLogin\UserLoginWebCookies;
use le7\Core\User\UserLoginInterface;
use le7\Core\User\Tokens\TokensDb;
use le7\Core\User\Tokens\TokensInterface;
use le7\Core\User\Passwords\PasswordsDb;
use le7\Core\User\Passwords\PasswordsInterface;
use le7\Core\Request\Request;
use le7\Core\Config\ConfigInterface;

class UserIdentityFactory {

    private ?PasswordsInterface $passwords = null;
    private ?TokensInterface $tokens = null;
    private ConfigInterface $config;
    private Request $request;

    public function __construct(ConfigInterface $config, Request $request) {
        $this->config = $config;
        $this->request = $request;
    }

    public function getUserLoginWeb(): UserLoginInterface {
        if ($this->config->getUserIdentity() === 'cookies') {
            $loginProvider = new UserLoginWebCookies($this->config, $this->request, $this->getTokens(), $this->getPasswords());
        }
        if ($this->config->getUserIdentity() === 'session') {
            $loginProvider = new UserLoginWebSession($this->getTokens(), $this->getPasswords());
        }
        return new UserLogin($loginProvider);
    }

    public function getUserLoginApi(): UserLoginInterface {
        $loginProvider = new UserLoginApi($this->getTokens());
        return new UserLogin($loginProvider);
    }

    public function getUserCheckWeb(): UserCheckInterface {
        $this->getTokens();
        if ($this->config->getUserIdentity() === 'cookies') {
            $check = new UserCheckWebCookies($this->request);
        }
        if ($this->config->getUserIdentity() === 'session') {
            $check = new UserCheckWebSession();
        }
        return new UserCheck($this->tokens, $check);
    }

    public function getUserCheckApi(): UserCheckInterface {
        $this->getTokens();
        $check = new UserCheckApi($this->request);
        return new UserCheck($this->tokens, $check);
    }

    public function getTokens(): TokensInterface {
        if ($this->tokens === null) {
            $this->tokens = new TokensDb();
        }
        return $this->tokens;
    }

    public function getVerificetionCode(): VerificationCodeInterface {
        return new VerificationCodeDb();
    }

    private function getPasswords(): PasswordsInterface {
        if ($this->passwords === null) {
            $this->passwords = new PasswordsDb();
        }
        return $this->passwords;
    }

    public function getUserWeb(): UserIdentityInterface {
        return new UserIdentity($this->getUserCheckWeb());
    }

    public function getUserApi(): UserIdentityInterface {
        return new UserIdentity($this->getUserCheckApi());
    }

}
