<?php

declare(strict_types=1);

namespace le7\Core\User\UserLogin;

use le7\Core\User\Passwords\PasswordsInterface;
use le7\Core\User\Tokens\TokensInterface;

class UserLoginWebSession implements UserLoginProvider {

    private array $errors = [];
    private PasswordsInterface $passwords;
    private TokensInterface $tokens;

    public function __construct(TokensInterface $tokens, PasswordsInterface $passwords) {
        $this->tokens = $tokens;
        $this->passwords = $passwords;
    }

    public function login(array $user, string $password): string|null {
        if (password_verify($password, $user['password'])) {
            if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $this->passwords->update($user['id'], $newHash);
            }
            $token = $this->tokens->create($user['id']);
            if ($token) {
                $_SESSION['user_token'] = $token;
                return $token;
            }
        }
        return null;
    }

    public function logout(string $token = ''): bool {
        $_SESSION['user_token'] = null;
        return $this->tokens->delete($token);
    }

    public function logoutAnywhere(): bool {
        
    }

    public function getErrors(): array {
        return $this->errors;
    }

}
