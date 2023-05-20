<?php

declare(strict_types=1);

namespace Core\ErrorHandler\Output;

use Core\Interfaces\ConfigInterface;
use Core\Interfaces\ErrorOutputCliInterface;
use Core\Console\ConsoleTrait;
use Core\Console\ColorMessage;
use \Throwable;

class ErrorToCli implements ErrorOutputCliInterface
{

    use ConsoleTrait;

    private ConfigInterface $config;
    private ColorMessage $color;

    public function __construct(ConfigInterface $config, ColorMessage $color)
    {
        $this->config = $config;
        $this->color = $color;
    }

    /**
     * @param Throwable|null $exception
     * @param array $errors
     */
    public function show(Throwable|null $exception, array $errors): void
    {
        $this->stderr(PHP_EOL);
        if (!empty($exception)) {
            $this->stderr($this->color->red("\r\nEXCEPTION: \r\n"));
            $this->stderr('  ' . $exception->getFile() . '(' . $exception->getLine() . ') ' . $exception->getMessage() . PHP_EOL);
            foreach ($exception->getTrace() as $traceItem) {
                if (!empty($traceItem['args'])) {
                    foreach ($traceItem['args'] as $arg) {
                        if (is_string($arg)) {
                            $this->stderr('   > ' . $arg . "\r\n");
                        }
                    }
                }
                if (isset($traceItem['file'])) {
                    $this->stderr('  > ' . $traceItem['file'] . (isset($traceItem['line']) ? '(' . $traceItem['line'] . ')' : ''));
                }
                if (isset($traceItem['function'])) {
                    $this->stderr((empty($traceItem['function']) ? '' : ' function: ' . $traceItem['function']));
                }
                if (isset($traceItem['class'])) {
                    $this->stderr((isset($traceItem['class']) ? ' class: ' . $traceItem['class'] : ''));
                }
                $this->stderr(PHP_EOL);
            }
        }
        if (!empty($errors)) {
            $this->stderr($this->color->red("ERRORS: \r\n"));
            foreach ($errors as $error) {
                $this->stderr('  ' . $this->color->darkGrey($error['errorType'] . ' '));
                $this->stderr($error['errorFile'] . '(' . $error['errorLine'] . '): ' . $error['errorString'] . "\r\n");
            }
        }
        $this->stderr('errors logged to ' . $this->config->string('loc.logs'));
        $this->stderr(PHP_EOL);
        exit;
    }

}
