<?php

declare(strict_types=1);

namespace le7\Core\User\UserLogin;

interface UserLoginProvider {

    public function login(array $user, string $password): string|null;

    public function logout(string $token = ''): bool;

    public function logoutAnywhere(): bool;

    public function getErrors(): array;
}
