<?php

namespace le7\Core\Database;

use RedBeanPHP\BeanHelper;

use \PDO;

interface DatabaseConnectionInterface {
    public function isConnected(): bool;
    public function connect(): void;
    public function selectDatabase(string $key, bool $force = FALSE): bool;
    public function addDatabase(string $key, string $dsn, string $user = NULL, null|string $pass = NULL, bool $frozen = FALSE, bool $partialBeans = FALSE, array $options = array(), BeanHelper $beanHelper = NULL): void;
    public function getPDO() : PDO;
}
