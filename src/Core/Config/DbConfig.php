<?php

namespace App\Core\Config;

class DbConfig implements DbConfigInterface {
    
    private array $configData;
    
    public function __construct(TopologyFsInterface $topology) {
        $this->configData = parse_ini_file($topology->getConfigUserPath() . '/dbconfig.ini', true);
    }

    public function getDbDriver(): string {
        return $this->configData['database']['dbdrvr'];
    }

    public function getDbFreeze(): bool {
        $value = $this->configData['database']['dbfreeze'];
        if (empty($value)) {
            return false;
        }
        return true;
    }

    public function getDbHost(): string {
        return $this->configData['database']['dbhost'];
    }

    public function getDbName(): string {
        return $this->configData['database']['dbname'];
    }

    public function getDbPass(): string {
        return $this->configData['database']['dbpass'];
    }

    public function getDbPrfx(): string {
        return $this->configData['database']['dbprfx'];
    }

    public function getDbUSer(): string {
        return $this->configData['database']['dbuser'];
    }

}
