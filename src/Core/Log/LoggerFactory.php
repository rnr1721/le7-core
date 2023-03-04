<?php

namespace App\Core\Log;

use App\Core\ErrorHandling\ErrorLogInterface;
use App\Core\Config\TopologyFsInterface;
use Psr\Log\LoggerInterface;

class LoggerFactory {
    
    private TopologyFsInterface $topology;

    private LoggerInterface $logSystem;
    private LoggerInterface $logUser;
    private ErrorLogInterface $errorLog;

    public function __construct(TopologyFsInterface $topologyFs) {
        $this->topology = $topologyFs;
    }
    
    public function getFileLogger($filename) : LoggerInterface {
        $logger = new Logger();
        $logger->addBroadcast(new LoggerRouteFile(['filePath' => $this->topology->getLogFolder() . DIRECTORY_SEPARATOR . $filename]));
        return $logger;
    }
    
    public function getUserLogger() : LoggerInterface {
        if (empty($this->logUser)) {
            $this->logUser = $this->getFileLogger('log.txt');
        }
        return $this->logUser;
    }

    public function getSystemLogger() : LoggerInterface {
        if (empty($this->logSystem)) {
            $this->logSystem = $this->getFileLogger('system.txt');
        }
        return $this->logSystem;
    }

    public function getErrorLog() : ErrorLogInterface {
        if (empty($this->errorLog)) {
            $this->errorLog = new ErrorLog($this->getSystemLogger());
        }
        return $this->errorLog;
    }
    
}
