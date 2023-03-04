<?php

namespace App\Core\User;

use App\Core\Database\DbConnInterface;
use \RedBeanPHP\OODBBean;

interface UserIdentityInterface {

    public function getUser(DbConnInterface $dbConnection): OODBBean|null;
}
