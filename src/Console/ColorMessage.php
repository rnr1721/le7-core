<?php

declare(strict_types=1);

namespace Core\Console;

/**
 * This class allow to show color messages in console
 */
class ColorMessage
{

    private string $default = "\033[39m";
    private array $color = [
        'default' => "\033[39m",
        'red' => "\033[31m",
        'green' => "\033[32m",
        'yellow' => "\033[33m",
        'blue' => "\033[34m",
        'magenta' => "\033[35m",
        'cyan' => "\033[36m",
        'light grey' => "\033[37m",
        'dark grey' => "\033[90m",
        'light red' => "\033[91m",
        'light green' => "\033[92m",
        'light yellow' => "\033[93m",
        'light blue' => "\033[94m",
        'light magenta' => "\033[95m"
    ];

    public function color(string $message, string $color): string
    {
        $result = $this->color[$color] ?? $this->default;
        $result .= $message . $this->default;
        return $result;
    }

    public function def(string $message): string
    {
        return $this->color($message, 'default');
    }

    public function red(string $message): string
    {
        return $this->color($message, 'red');
    }

    public function green(string $message): string
    {
        return $this->color($message, 'green');
    }

    public function yellow(string $message): string
    {
        return $this->color($message, 'yellow');
    }

    public function blue(string $message): string
    {
        return $this->color($message, 'blue');
    }

    public function magenta(string $message): string
    {
        return $this->color($message, 'magenta');
    }

    public function cyan(string $message): string
    {
        return $this->color($message, 'cyan');
    }

    public function lightGrey(string $message): string
    {
        return $this->color($message, 'light grey');
    }

    public function darkGrey(string $message): string
    {
        return $this->color($message, 'dark grey');
    }

    public function lightRed(string $message): string
    {
        return $this->color($message, 'light red');
    }

    public function lightGreen(string $message): string
    {
        return $this->color($message, 'light green');
    }

    public function lightYellow(string $message): string
    {
        return $this->color($message, 'light yellow');
    }

    public function lightBlue(string $message): string
    {
        return $this->color($message, 'light blue');
    }

    public function lightMagenta(string $message): string
    {
        return $this->color($message, 'light magenta');
    }

}
