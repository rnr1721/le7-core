<?php

declare(strict_types=1);

namespace le7\Core\User;

use le7\Core\User\UserCheck\UserCheckProvider;
use le7\Core\User\Tokens\TokensInterface;

class UserCheck implements UserCheckInterface {

    private UserCheckProvider $userCheck;
    private TokensInterface $tokens;

    public function __construct(TokensInterface $tokens, UserCheckProvider $userCheck) {
        $this->tokens = $tokens;
        $this->userCheck = $userCheck;
    }

    public function getUserId(): int|null {
        $token = $this->getToken();
        return intval($this->tokens->getUserId($token)) ?? null;
    }

    public function getToken(): string|null {
        return $this->userCheck->getToken();
    }

}
