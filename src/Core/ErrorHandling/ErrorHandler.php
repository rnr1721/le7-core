<?php

declare(strict_types=1);

namespace le7\Core\ErrorHandling;

use le7\Core\Config\ConfigInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ErrorHandler {

    private LoggerInterface $log;
    private ErrorCollector $errorCollector;

    public function __construct(ConfigInterface $config, ErrorCollector $errorCollector,LoggerInterface $log) {
        $this->errorCollector = $errorCollector;
        $this->log = $log;
        if ($config->getErrorReporting()) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', '0');
            ini_set('display_startup_errors', '0');
            error_reporting(E_USER_ERROR);
        }
        set_error_handler(array($this, 'handleError'));
        set_exception_handler(array($this,'handleException'));
        register_shutdown_function(array($this,'shutdown'));
    }

    public function handleError(int $errno, string $errstr, string $errfile, array|int $errline): bool {
        $this->errorCollector->registerError($errno,$errstr,$errfile,$errline);
        $this->log->log($errno,$errstr.': '.$errfile.' => '. $errline);
        return true;
    }

    /**
     * @param Throwable $exception
     */
    public function handleException(Throwable $exception) {
        $this->log->log($exception->getCode(),$exception->getMessage().': '.$exception->getFile().' => '. $exception->getLine());
        $this->errorCollector->registerException($exception);
    }

    private function shutdown() {
        if (!empty($this->errorCollector->isErrors())) {
            $this->errorCollector->output();
        }
    }

}
