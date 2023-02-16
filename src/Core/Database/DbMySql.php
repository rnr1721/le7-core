<?php

declare(strict_types=1);

namespace le7\Core\Database;

use RedBeanPHP\R;

class DbMySql implements DbInterface {

    public function connect(string $dbHost, string $dbName, string $dbUser, string $dbPassword): void {
        R::setup("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword); //for both mysql or mariaDB
    }

}
