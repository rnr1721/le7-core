<?php

namespace App\Core\Helpers;

class ConsoleHelper
{

    public function colorMessage(string $message,string $color) : string {
        $result = match ($color) {
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
        };
        $result .= $message . "\033[39m";
        return $result;
    }

}
