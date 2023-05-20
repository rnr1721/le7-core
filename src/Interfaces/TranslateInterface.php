<?php

declare(strict_types=1);

namespace Core\Interfaces;

interface TranslateInterface
{

    public function get(string|array $var, string $lang = '', string $returnIfEmpty = 'en', bool $reportEmpty = false): string;

    public function set(string $data = '', string|array $var = '', string $lang = ''): string;

    public function getAsArray(string $value = ''): array;

    public function getDbField(string $fieldName = '', string $value = '', string $lang = ''): string;

    public function getUntranslated(string $value): array;

    public function transliterate(string $textcyr = ''): string;
}
