<?php

namespace le7\Core\User;

use le7\Core\User\UserIdentityInterface;
use le7\Core\Database\DatabaseConnectionInterface;
use \RedBeanPHP\OODBBean;
use \RedBeanPHP\R;

class UserIdentity implements UserIdentityInterface {

    private UserCheckInterface $userCheck;

    public function __construct(UserCheckInterface $userCheck) {
        $this->userCheck = $userCheck;
    }

    public function getUser(DatabaseConnectionInterface $dbConnection): OODBBean|null {
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
