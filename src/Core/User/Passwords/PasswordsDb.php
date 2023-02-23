<?php

namespace le7\Core\User\Passwords;

use RedBeanPHP\R;

class PasswordsDb implements PasswordsInterface {
    
    public function update(string $userId, string $hash): bool {
        $record = R::findOne('users',' id = ? ',[$userId]);
        if (!$record) {
            return false;
        }
        $record->password = $hash;
        R::store($record);
    }

}
