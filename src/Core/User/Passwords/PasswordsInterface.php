<?php

namespace App\Core\User\Passwords;

interface PasswordsInterface {

    public function update(string $userId, string $hash): bool;

    public function create(string $passwordUnencrypted): string;

    public function needRehash(string $encryptedPassword): bool;

    public function verify(string $password, string $hash): bool;
}
