<?php

declare(strict_types=1);

namespace App\Core\User\Tokens;

use RedBeanPHP\R;

class TokensDb implements TokensInterface {

    public function create(int|string $userId, string $info = ''): string|null {
        if (empty($userId)) {
            return null;
        }
        $token = bin2hex(random_bytes(64));
        $tokenRecord = R::dispense('usertokens');
        $tokenRecord->token = $token;
        $tokenRecord->user_id = $userId;
        $tokenRecord->info = $info;
        R::store($tokenRecord);
        return $token;
    }

    public function delete(string $token): bool {
        if (empty($token)) {
            return false;
        }
        $victim = R::findOne('usertokens', ' token = ? ', [$token]);
        if ($victim) {
            R::trash($victim);
            return true;
        }
        return false;
    }

    public function deleteAll(int $userId): bool {
        
    }

    public function getAll(int $userId): array {
        $tokens = R::getAll('SELECT * FROM usertokens WHERE user_id = ? ', [$userId]);
        if (is_array($tokens)) {
            return $tokens;
        }
        return [];
    }

    public function getUserId(string|null $token): int|null {
        if ($token === null) {
            return null;
        }
        $t = R::findOne('usertokens', ' token = ? ', [$token]);
        if (!$t) {
            return null;
        }
        if (is_string($token)) {
            return intval($t->user_id);
        }
    }

}
