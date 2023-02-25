<?php

declare(strict_types=1);

namespace le7\Core\ErrorHandling;

use Psr\Log\LoggerInterface;
use \Exception;

class ErrorLog implements ErrorLogInterface
{

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function callError(Exception $e): void
    {
        //print_r($e);
        //exit;
        /*
        $lines = array();
        $lines[] = $e->getMessage() . ' => ' . $e->getFile() . ' (' . $e->getLine() . ')';
        foreach ($e->getTrace() as $line) {
            $function = ($line['function'] ?? '');
            $class = (empty($line['class']) ? '' : 'class:' . $line['class']);
            $file = (empty($line['file']) ? '' : 'file:' . $line['file']);
            $cline = (empty($line['line']) ? '' : ' (' . $line['line'] . ')');
            $lines[] = "$file$function$class$cline";
        }
        foreach ($lines as $line) {
            $this->logger->log($e->getCode(), $line);
        }
*/
        $this->logger->error($e->getMessage(),['exception' => $e]);
        trigger_error($e->getMessage(), E_USER_ERROR);
    }

}
