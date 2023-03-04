<?php

declare(strict_types=1);

namespace App\Core\User\UserLogin;

use App\Core\Config\ConfigInterface;
use App\Core\Request\Request;
use App\Core\User\Passwords\PasswordsInterface;
use App\Core\User\Tokens\TokensInterface;

class UserLoginWebCookies implements UserLoginProvider {

    private int $storeTime = 2592000;
    private array $errors = [];
    private ConfigInterface $config;
    private Request $request;
    private PasswordsInterface $passwords;
    private TokensInterface $tokens;

    public function __construct(ConfigInterface $config, Request $request, TokensInterface $tokens, PasswordsInterface $passwords) {
        $this->request = $request;
        $this->tokens = $tokens;
        $this->passwords = $passwords;
        $this->config = $config;
    }

    public function login(array $user, string $password): string|null {
        // Two factor authentification

        if ($this->passwords->verify($password, $user['password'])) {
            if ($this->passwords->needRehash($user['password'])) {
                $newHash = $this->passwords->create($password);
                $this->passwords->update($user['id'], $newHash);
            }
            $userAgent = $this->request->getServerParam('HTTP_USER_AGENT') ?? '';
            $token = $this->tokens->create($user['id'], $userAgent);
            if ($token) {
                $secureCookie = $this->config->getIsProduction();
                $sameSite = $this->config->getSessionCookieSamesite();
                $userLogin = $user['username'];
                $hash = $this->passwords->create($userLogin . $userAgent);
                setcookie('user_login', $userLogin, [
                    'expires' => time() + $this->storeTime,
                    'path' => '/',
                    'secure' => $secureCookie,
                    'samesite' => $sameSite,
                ]);

                setcookie('user_credential', $hash, [
                    'expires' => time() + $this->storeTime,
                    'path' => '/',
                    'secure' => $secureCookie,
                    'samesite' => $sameSite,
                ]);

                setcookie('user_token', $token, [
                    'expires' => time() + $this->storeTime,
                    'path' => '/',
                    'secure' => $secureCookie,
                    'samesite' => $sameSite,
                ]);

                return $token;
            }
        }
        return null;
    }

    public function logout(string $token = ''): bool {
        if ($token === '') {
            $token = $this->request->getCookieParam('user_token');
            if (!$token) {
                return false;
            }
        }
        $this->deleteCookie('user_login');
        $this->deleteCookie('user_credential');
        $this->deleteCookie('user_token');
        return $this->tokens->delete($token);
    }

    private function deleteCookie(string $cookieName): void {
        if ($this->request->getCookieParam($cookieName)) {
            unset($_COOKIE[$cookieName]);
            setcookie($cookieName, '', time() - 3600, '/');
        }
    }

    public function logoutAnywhere(): bool {
        
    }

    public function getErrors(): array {
        return $this->errors;
    }

}
