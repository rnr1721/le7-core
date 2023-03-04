<?php

namespace App\Core\User\Passwords;

use RedBeanPHP\R;

class PasswordsDb implements PasswordsInterface {

    public function update(string $userId, string $hash): bool {
        $record = R::findOne('users', ' id = ? ', [$userId]);
        if (!$record) {
            return false;
        }
        $record->password = $hash;
        R::store($record);
    }

    public function create(string $passwordUnencrypted): string {
        return password_hash($passwordUnencrypted, PASSWORD_DEFAULT);
    }

    public function needRehash(string $encryptedPassword): bool {
        return password_needs_rehash($encryptedPassword, PASSWORD_DEFAULT);
    }

    public function verify(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

}
