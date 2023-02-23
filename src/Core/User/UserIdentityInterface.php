<?php

namespace le7\Core\User;

use le7\Core\Database\DatabaseConnectionInterface;
use \RedBeanPHP\OODBBean;

interface UserIdentityInterface {

    public function getUser(DatabaseConnectionInterface $dbConnection): OODBBean|null;
}
