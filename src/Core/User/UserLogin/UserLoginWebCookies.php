<?php

declare(strict_types=1);

namespace le7\Core\User\UserLogin;

use le7\Core\Config\ConfigInterface;
use le7\Core\Request\Request;
use le7\Core\User\Passwords\PasswordsInterface;
use le7\Core\User\Tokens\TokensInterface;

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

        if (password_verify($password, $user['password'])) {
            if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $this->passwords->update($user['id'], $newHash);
            }
            $token = $this->tokens->create($user['id']);
            if ($token) {
                $secureCookie = $this->config->getIsProduction();
                $sameSite = $this->config->getSessionCookieSamesite();
                $userLogin = $user['username'];
                $userAgent = $this->request->getServerParam('HTTP_USER_AGENT');
                $hash = password_hash($userLogin . $userAgent, PASSWORD_DEFAULT);
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
        $this->errors[] = _('Incorrect user or password');
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
