<?php

namespace App\Core\User;

use App\Core\User\UserIdentityInterface;
use App\Core\Database\DbConnInterface;
use \RedBeanPHP\OODBBean;
use \RedBeanPHP\R;

class UserIdentity implements UserIdentityInterface {

    private UserCheckInterface $userCheck;

    public function __construct(UserCheckInterface $userCheck) {
        $this->userCheck = $userCheck;
    }

    public function getUser(DbConnInterface $dbConnection): OODBBean|null {
        $userToken = $this->userCheck->getToken();
        if ($userToken !== null) {
            $dbConnection->connect();
            $userId = $this->userCheck->getUserId();
            if ($userId !== null) {
                return R::findOne('user', ' id = ? ', [$userId]);
            }
        }
        return null;
    }

}
