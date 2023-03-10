<?php

namespace App\Core\User\Verification;

use RedBeanPHP\R;

class VerificationCodeDb implements VerificationCodeInterface {

    public function getCode(string|int $userId): int|null {
        $user = R::findOne('user', ' id = ? ', [$userId]);
        if ($user) {
            return intval($user->vcode);
        }
        return null;
    }

    public function setCode(string|int $userId): string|null {
        $user = R::findOne('user', ' id = ? ', [$userId]);
        if ($user) {
            $code = strval(mt_rand(1111, 9999));
            $user->vcode = $code;
            R::store($user);
            return $code;
        }
        return null;
    }

    public function deleteCode(string|int $userId): bool {
        $user = R::findOne('user', ' id = ? ', [$userId]);
        if ($user) {
            $user->vcode = '0';
            R::store($user);
            return true;
        }
        return false;
    }

    public function verifyCode(string|int $userId, string $code): bool {
        $user = R::findOne('user', ' id = ? ', [$userId]);
        if ($user) {
            if (strval($user->vcode) === strval($code)) {
                return true;
            }
        }
        return false;
    }

}
