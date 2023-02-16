<?php

declare(strict_types=1);

namespace le7\Core\Database;

interface DbInterface {

    public function connect(string $dbHost, string $dbName, string $dbUser, string $dbPassword): void;
}
