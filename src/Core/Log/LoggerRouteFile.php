<?php

namespace le7\Core\Log;

use Stringable;

class LoggerRouteFile extends LoggerRoute
{

    protected bool $isEnable = true;

    public string $filePath;

    public string $template = "{date} {level} {message} {context}";

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);

        if (!file_exists($this->filePath))
        {
            touch($this->filePath);
        }
    }

    public function log($level, Stringable|string $message, array $context = []): void
    {
        file_put_contents($this->filePath, trim(strtr($this->template, [
                '{date}' => $this->getDate(),
                '{level}' => $level,
                '{message}' => $message,
                '{context}' => $this->toString($context),
            ])) . PHP_EOL, FILE_APPEND);
    }

}
