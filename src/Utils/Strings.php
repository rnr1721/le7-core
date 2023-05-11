<?php

namespace Core\Utils;

class Strings
{

    /**
     * Remove new lines from string
     * @param string $string String to process
     * @return string
     */
    public static function removeNewLine(string $string): string
    {
        return trim(preg_replace('/\s\s+/', ' ', $string));
    }

    /**
     * Convert doc comment to string
     * @param string $string Doc comment for process
     * @return string
     */
    public static function parseDocComment(string $string): string
    {
        return trim(str_replace(array('/', '*'), '', substr($string, 0, intval(strpos($string, '@')))));
    }

    /**
     * Check if string is JSON
     * @param string $string Potentially JSON string
     * @return bool
     */
    public static function isJson(string $string): bool
    {
        return json_decode($string) !== null;
    }

    /**
     * Count symbols in string
     * @param string $string
     * @return int
     */
    public static function countChars(string $string): int
    {
        return strlen($string);
    }

    /**
     * All symbols in string to uppercase
     * @param string $string String to process
     * @return string
     */
    public static function toUpperCase(string $string): string
    {
        return strtoupper($string);
    }

    /**
     * All symbols in string to lowercase
     * @param string $string String to process
     * @return string
     */
    public static function toLowerCase(string $string): string
    {
        return strtolower($string);
    }

    /**
     * Replace text in string
     * @param string $string String to process
     * @param string $search Text to search
     * @param string $replace Text to replace
     * @return string
     */
    public static function replace(string $string, string $search, string $replace): string
    {
        return str_replace($search, $replace, $string);
    }

    /**
     * Remove whitespaces from string
     * @param string $string String to process
     * @return string
     */
    public static function trim(string $string): string
    {
        return trim($string);
    }

    /**
     * Get array from string by separator
     * @param string $string String to process
     * @param non-empty-string $separator Separator
     * @return array
     */
    public static function explode(string $string, string $separator): array
    {
        return explode($separator, $string);
    }

    /**
     * Convert array to string with separator
     * @param array<array-key, string> $array Array to process
     * @param string $separator Separator in string
     * @return string
     */
    public static function implode(array $array, string $separator): string
    {
        return implode($separator, $array);
    }

}
