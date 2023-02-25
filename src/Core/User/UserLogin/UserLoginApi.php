<?php

declare(strict_types=1);

namespace le7\Core\User\UserLogin;

use le7\Core\Request\Request;
use le7\Core\User\Passwords\PasswordsInterface;
use le7\Core\User\Tokens\TokensInterface;
use \Exception;

class UserLoginApi implements UserLoginProvider {

    private array $errors = [];
    private Request $request;
    private PasswordsInterface $passwords;
    private TokensInterface $tokens;

    public function __construct(Request $request, TokensInterface $tokens, PasswordsInterface $passwords) {
        $this->tokens = $tokens;
        $this->passwords = $passwords;
        $this->request = $request;
    }

    public function login(array $user, string $password): string|null {
        if ($user['active'] === '0') {
            $message = _('User blocked');
            $this->errors[] = $message;
            throw new Exception($message);
        }
        if ($this->passwords->verify($password, $user['password'])) {
            if ($this->passwords->needRehash($user['password'])) {
                $newHash = $this->passwords->create($password);
                $this->passwords->update($user['id'], $newHash);
            }
            $token = $this->tokens->create($user['id']);
            return $token;
        }
        return null;
    }

    public function logout(string $token = ''): bool {
        if (empty($token)) {
            $token = $this->request->getHeaderLine('X-Auth-Token');
        }
        return $this->tokens->delete($token);
    }

    public function logoutAnywhere(): bool {
        
    }

    public function getErrors(): array {
        return $this->errors;
    }

}
