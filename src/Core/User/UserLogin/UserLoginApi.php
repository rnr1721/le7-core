<?php

declare(strict_types=1);

namespace le7\Core\User\UserLogin;

use le7\Core\User\Tokens\TokensInterface;

class UserLoginApi implements UserLoginProvider {

    private array $errors = [];
    private TokensInterface $tokens;

    public function __construct(TokensInterface $tokens) {
        $this->tokens = $tokens;
    }

    public function login(array $user, string $password): string|null {
        if ($user['active'] === '0') {
            $message = _('User blocked');
            $this->errors[] = $message;
            throw new Exception($message);
        }
        if (password_verify($password, $user['password'])) {
            if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $this->passwords->update($user['id'], $newHash);
            }
            $token = $this->tokens->create($user['id']);
            return $token;
        }
        return null;
    }

    public function logout(string $token): bool {
        return $this->tokens->delete($token);
    }

    public function logoutAnywhere(): bool {
        
    }

    public function getErrors(): array {
        return $this->errors;
    }

}
