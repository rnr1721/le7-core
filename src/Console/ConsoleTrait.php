<?php

declare(strict_types=1);

namespace Core\Console;

trait ConsoleTrait
{

    /**
     * Output to stderr
     *
     * @param string $line Line content
     * @param bool $newline From new line
     * @return int|false Returns the number of bytes written or `false` on failure.
     */
    protected function stderr(string $line, bool $newline = true): int|false
    {
        if ($newline) {
            $line .= "\r\n";
        }
        return fwrite(\STDERR, $line);
    }

    /**
     * Output to stdout
     *
     * @param string $line Line content
     * @param bool $newline From new line
     * @return int|false Returns the number of bytes written or `false` on failure.
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
     *
     * @param bool $raw
     * @param string|null $prompt
     * @return string|false Returns the input string or `false` on failure.
     */
    protected function stdin(bool $raw = false, ?string $prompt = null): string|false
    {
        if ($prompt !== null) {
            fwrite(STDOUT, $prompt);
        }
        return $raw ? fgets(\STDIN) : rtrim(fgets(\STDIN), PHP_EOL);
    }

    /**
     * Read input from console with hidden characters (e.g., password input)
     *
     * @param string|null $prompt
     * @return string|false Returns the input string or `false` on failure.
     */
    protected function hiddenInput(?string $prompt = null): string|false
    {
        if ($prompt !== null) {
            fwrite(STDOUT, $prompt);
        }
        system('stty -echo');
        $input = rtrim(fgets(\STDIN), PHP_EOL);
        system('stty echo');
        fwrite(STDOUT, "\r\n");
        return $input;
    }

    /**
     * Output message and get user's choice from the given options
     *
     * @param string $message
     * @param array $options
     * @param string|null $default
     * @return string The selected option.
     */
    protected function choice(string $message, array $options, ?string $default = null): string
    {
        $validOptions = array_map('strtolower', $options);
        $defaultOption = ($default !== null && in_array(strtolower($default), $validOptions)) ? strtolower($default) : null;

        fwrite(STDOUT, $message . ' [' . implode('/', $options) . ']' . ($defaultOption !== null ? ' [' . $defaultOption . ']' : '') . ': ');

        $input = strtolower(trim(fgets(\STDIN)));

        if ($input === '' && $defaultOption !== null) {
            return $defaultOption;
        }

        while (!in_array($input, $validOptions)) {
            fwrite(STDOUT, 'Invalid option. Please choose one of ' . implode('/', $options) . ': ');
            $input = strtolower(trim(fgets(\STDIN)));
        }

        return $input;
    }

    /**
     * Output an array as a table
     *
     * @param array $data
     * @param string $delimiter
     */
    public function outputTable(array $data, string $delimiter = '|'): void
    {
        if (count($data) === 0) {
            return;
        }

        // Get the keys from the first row as column headers
        $headers = array_keys($data[0]);

        // Determine the maximum width for each column
        $columnWidths = [];
        /** @var string $header */
        foreach ($headers as $header) {
            //$columnWidths[$header] = max(array_map('strlen', array_column($data, $header))) + 2;
            $columnWidths[$header] = max(array_map('strlen', array_column($data, $header)) ?: [0]) + 2;
        }

        // Output the headers
        $this->printTableRow($headers, $columnWidths, $delimiter);

        // Output the data rows
        foreach ($data as $row) {
            $this->printTableRow($row, $columnWidths, $delimiter);
        }
    }

    /**
     * Print a row of data in table format
     * @param array $data
     * @param array $columnWidths
     * @param string $delimiter
     */
    private function printTableRow(array $data, array $columnWidths, string $delimiter): void
    {
        foreach ($data as $key => $value) {
            printf("%-{$columnWidths[$key]}s", $value . ' ' . $delimiter);
        }
        echo PHP_EOL;
    }

    /**
     * Display a progress bar
     *
     * @param int $current
     * @param int $total
     * @param int $width
     */
    public function showProgressBar(int $current, int $total, int $width = 50): void
    {
        $percentage = ($total > 0) ? ($current / $total) * 100 : 100;
        $filledWidth = (int) round(($width * $percentage) / 100);
        $emptyWidth = $width - $filledWidth;

        $progressBar = '[' . str_repeat('=', $filledWidth) . str_repeat(' ', $emptyWidth) . '] ' . sprintf('%0.2f', $percentage) . '%';

        // Output the progress bar
        fwrite(STDOUT, "\r" . $progressBar);

        // Flush the output buffer
        fflush(STDOUT);

        // If the progress is complete, add a new line
        if ($current === $total) {
            fwrite(STDOUT, PHP_EOL);
        }
    }

}
