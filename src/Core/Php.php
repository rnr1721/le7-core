<?php

declare(strict_types=1);

namespace le7\Core;

use le7\Core\Config\ConfigInterface;
use le7\Core\Config\TopologyFsInterface;

class Php {

    private ConfigInterface $config;
    private TopologyFsInterface $topology;

    public function __construct(ConfigInterface $config, TopologyFsInterface $topology) {
        $this->config = $config;
        $this->topology = $topology;
    }

        public function setHttpOnly(bool $value = true) : void {
            ini_set('session.cookie_httponly', $value);
        }
        
        public function setSessionCookieSecure(bool $value = true) : void {
            ini_set('session.cookie_secure', $value); 
        }

        public function setSessionCookieSameSite(string $value = "Lax") : void {
            ini_set('session.cookie_samesite', $value);
        }
        
        public function setSessionPath(string $sessionPath) : void {
            ini_set('session.save_path', $sessionPath);
        }
        
        public function setTimezone(string $timeZone) : void {
            date_default_timezone_set($timeZone);
        }

        public function setErrorReporting(int $value) : void {
            error_reporting($value);
        }
        
        public function setIni(string $item, mixed $value) {
            ini_set($item, $value);
        }
        
}
