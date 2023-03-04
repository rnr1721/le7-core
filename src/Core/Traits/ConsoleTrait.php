<?php

namespace App\Core\Traits;

trait ConsoleTrait {

    protected function stderr(string $line) {
        return fwrite(\STDERR, $line);
    }

    protected function stdout(string $line) {
        return fwrite(\STDOUT, $line);
    }

    public static function stdin($raw = false) {
        return $raw ? fgets(\STDIN) : rtrim(fgets(\STDIN), PHP_EOL);
    }

}
