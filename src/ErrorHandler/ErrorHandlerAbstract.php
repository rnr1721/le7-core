<?php

declare(strict_types=1);

namespace Core\ErrorHandler;

use Core\Interfaces\MessageCollection;
use Core\Interfaces\Config;
use Psr\Log\LoggerInterface;
use \Throwable;

abstract class ErrorHandlerAbstract
{

    protected MessageCollection $messages;
    protected ?Throwable $exception = null;
    protected array $errors = [];
    protected LoggerInterface $logger;

    public function __construct(Config $config, LoggerInterface $logger, MessageCollection $messages)
    {
        $this->messages = $messages;
        $this->logger = $logger;
        if ($config->bool('errorReporting', true)) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', '0');
            ini_set('display_startup_errors', '0');
            error_reporting(E_USER_ERROR);
        }
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
    }

    public function handleError(int $errno, string $errstr, string $errfile, array|int $errline): bool
    {
        $errorType = $this->getErrorTypeAsString($errno);
        $this->errors[] = [
            'errorType' => $errorType,
            'errorString' => $errstr,
            'errorFile' => $errfile,
            'errorLine' => $errline
        ];
        $this->logger->log($errno, $errstr . ': ' . $errfile . ' => ' . $errline);
        return true;
    }

    /**
     * @param Throwable $exception
     */
    public function handleException(Throwable $exception): void
    {
        $this->logger->error($exception);
        $this->exception = $exception;
    }

    public function isErrors(): bool
    {
        if ($this->exception === null && count($this->errors) === 0) {
            return false;
        }
        return true;
    }

    public function getErrorTypeAsString(int $type): string
    {
        switch ($type) {
            case 0: // 1 //
                return 'PIZDETS';
            case E_ERROR: // 1 //
                return 'E_ERROR';
            case E_WARNING: // 2 //
                return 'E_WARNING';
            case E_PARSE: // 4 //
                return 'E_PARSE';
            case E_NOTICE: // 8 //
                return 'E_NOTICE';
            case E_CORE_ERROR: // 16 //
                return 'E_CORE_ERROR';
            case E_CORE_WARNING: // 32 //
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR: // 64 //
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING: // 128 //
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR: // 256 //
                return 'E_USER_ERROR';
            case E_USER_WARNING: // 512 //
                return 'E_USER_WARNING';
            case E_USER_NOTICE: // 1024 //
                return 'E_USER_NOTICE';
            case E_STRICT: // 2048 //
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR: // 4096 //
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED: // 8192 //
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED: // 16384 //
                return 'E_USER_DEPRECATED';
        }
        return "";
    }

}
