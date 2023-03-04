<?php

declare(strict_types=1);

namespace App\Core\Config;

class UserConfig implements UserConfigInterface {

    private TopologyFsInterface $topology;
    private object $configData;

    public function __construct(TopologyFsInterface $topology) {
        $this->topology = $topology;
        $config = (object) (parse_ini_file($topology->getConfigUserPath() . '/user.ini', true) ?? array());
        $this->configData = json_decode (json_encode ($config), FALSE);
    }

    public function __get(string $name): object|string|null {
        if (property_exists($this->configData,$name)) {
            if (!is_array($this->configData->$name)) {
                return $this->configData->$name;
            }
        }
        return null;
    }
    
}
