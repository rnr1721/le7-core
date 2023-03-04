<?php

namespace App\Core\Helpers;

class StringHelper
{

    private array $htmlEntities = array(
        '&quot;' => "\"",
        '&ndash;' => '–',
        '&deg;' => '°',
        '&sup2;' => '²',
        '&sup3;' => '³',
        '&nbsp;' => ' ',
        '&empty;' => '∅',
        '&emptyset;' => '∅',
        '&ang;' => '∠',
        '&Oslash;' => 'Ø',
        '&oslash;' => 'ø',
        '&osol;' => '⊘',
        '&hellip;' => '...',
        '&laquo;' => '«',
        '&raquo;' => '»',
    );

    public function endsWith(string $FullStr, string $needle): bool
    {
        $StrLen = strlen($needle);
        $FullStrEnd = substr($FullStr, strlen($FullStr) - $StrLen);
        return $FullStrEnd == $needle;
    }

    function unTrailingSlashIt(string $string): string
    {
        return rtrim($string, '/\\');
    }

    function TrailingSlashIt(string $string): string
    {
        return $this->unTrailingSlashIt($string) . '/';
    }

    public function getAlphabetEnAsArray(): array
    {
        return range('A', 'Z');
    }

    public function unHtmlEntities(string $string) : string
    {
        foreach ($this->htmlEntities as $entity => $value) {
            $string = str_replace($entity,$value,$string);
        }
        return $string;
    }

    public function isJson(string $string): bool
    {
        json_decode($string);
        if (is_numeric($string)) {
            return false;
        } else {
            $result = (json_last_error() == JSON_ERROR_NONE);
            if ($result == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

}
