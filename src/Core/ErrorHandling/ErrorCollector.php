<?php

declare(strict_types=1);

namespace le7\Core\ErrorHandling;

use le7\Core\Messages\MessageCollectionInterface;
use Throwable;

class ErrorCollector {

    /**
     * @var Throwable
     */
    protected Throwable $exception;

    /**
     * @var array
     */
    protected array $errors = array();
    protected ErrorInterface $errorOutput;
    protected ErrorCodes $errorCodes;
    protected MessageCollectionInterface $messageCollection;

    /**
     * ErrorView constructor.
     * @param ErrorInterface $errorOutput
     * @param ErrorCodes $errorCodes
     */
    public function __construct(ErrorInterface $errorOutput, ErrorCodes $errorCodes, MessageCollectionInterface $messageCollection) {
        $this->errorOutput = $errorOutput;
        $this->errorCodes = $errorCodes;
        $this->messageCollection = $messageCollection;
    }

    /**
     * @param Throwable $exception
     */
    public function registerException(Throwable $exception): void {
        $this->exception = $exception;
    }

    public function registerError(int $errNumber, string $errString, string $errFile, array|int $errLine): void {
        $errorType = $this->errorCodes->getErrorTypeAsString($errNumber);
        $error = array(
            'errorType' => $errorType,
            'errorString' => $errString,
            'errorFile' => $errFile,
            'errorLine' => $errLine
        );
        $this->messageCollection->newMsg($errorType . ' ' . $errString . ' > ' . $errFile . ' (' . $errLine . ')', 'error');
        $this->errors[] = $error;
    }

    public function isErrors(): bool {
        if (!empty($this->exception) or!empty($this->errors)) {
            return true;
        }
        return false;
    }

    public function output() {
        $exception = empty($this->exception) ? null : $this->exception;
        $this->errorOutput->show($exception, $this->errors);
    }

    public function getErrorsArray(): array {
        return $this->errors;
    }

}
