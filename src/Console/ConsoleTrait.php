<?php

declare(strict_types=1);

namespace Core\Console;

trait ConsoleTrait
{

    /**
     * Output to stderr
     * @param string $line Line content
     * @param bool $newline From new line
     * @return int|false
     */
    protected function stderr(string $line, bool $newline = true): int|false
    {
        if ($newline) {
            $line .= "\r\n";
        }
        return fwrite(\STDERR, $line);
    }

    /**
     * 
     * @param string $line Line content
     * @param bool $newline From new line
     * @return int|false
     */
    protected function stdout(string $line, bool $newline = true): int|false
    {
        if ($newline) {
            $line .= "\r\n";
        }
        return fwrite(\STDOUT, $line);
    }

    /**
     * Input from console
     * @param bool $raw
     * @return string|false
     */
    public static function stdin(bool $raw = false): string|false
    {
        return $raw ? fgets(\STDIN) : rtrim(fgets(\STDIN), PHP_EOL);
    }

}
