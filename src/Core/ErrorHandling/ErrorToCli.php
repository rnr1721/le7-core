<?php

declare(strict_types=1);

namespace le7\Core\ErrorHandling;

use le7\Core\Traits\ConsoleTrait;
use le7\Core\Config\ConfigInterface;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Helpers\ConsoleHelper;
use Throwable;

class ErrorToCli extends ErrorToMain implements ErrorInterface
{

    use ConsoleTrait;
    
    private ConsoleHelper $consoleHelper;


    public function __construct(ConfigInterface $config, TopologyFsInterface $topology, ConsoleHelper $consoleHelper) {
        parent::__construct($config, $topology);
        $this->consoleHelper = $consoleHelper;
    }

        /**
     * @param Throwable|null $exception
     * @param array $errors
     */
    public function show(Throwable|null $exception, array $errors): void
    {
        $this->stderr(PHP_EOL);
        if (!empty($exception)) {
            $this->stderr($this->consoleHelper->colorMessage("\r\nEXCEPTION: \r\n","red"));
            $this->stderr('  ' . $exception->getFile() . '(' . $exception->getLine() . ') ' . $exception->getMessage() . PHP_EOL);
            foreach ($exception->getTrace() as $traceItem) {
                if (!empty($traceItem['args'])) {
                    foreach ($traceItem['args'] as $arg) {
                        $this->stderr('   > ' . $arg . "\r\n");
                    }
                }
                $this->stderr('  > ' . $traceItem['file'] . ' (' . $traceItem['line'] . ")");
                $this->stderr((empty($traceItem['function']) ? '' : ' function: ' . $traceItem['function']));
                $this->stderr((empty($traceItem['class']) ? '' : ' class: ' . $traceItem['class']));
                $this->stderr(PHP_EOL);
            }
        }
        if (!empty($errors)) {
            $this->stderr($this->consoleHelper->colorMessage("ERRORS: \r\n","red"));
            foreach ($errors as $error) {
                $this->stderr('  ' . $this->consoleHelper->colorMessage($error['errorType'].' ',"dark grey"));
                $this->stderr($error['errorFile'] . '(' . $error['errorLine'] . '): ' . $error['errorString'] . "\r\n");
            }
        }
        $this->stderr('errors logged to ' . $this->topology->getLogFolder());
        $this->stderr(PHP_EOL);
        exit;
    }

}
