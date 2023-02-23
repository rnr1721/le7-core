<?php

namespace le7\Core\User\Passwords;

interface PasswordsInterface {

    public function update(string $userId, string $hash): bool;
}
