<?php

namespace App\Core\Locales;

interface LocalesInterface {

    public function setLocale(string $localeShortname): bool;

    public function getLocaleByName(string $localeName): array;

    public function getLocaleLabelByName(string $localeName): string;

    public function getLocaleShortnameByName(string $localeName): string;

    public function getLocaleByShortname(string $localeShortname): array;

    public function getLocaleLabelByShortname(string $localeShortname): string;

    public function getLocaleNameByShortname(string $localeShortname): string;

    public function getLocalesByName(): array;

    public function getLocalesByShortname(): array;

    public function getCurrentLocaleName(): string;

    public function getCurrentLocaleShortname(): string;

    public function getCurrentLocaleLabel(): string;

    public function getDefaultLocaleName(): string;

    public function getDefaultLocaleShortname(): string;

    public function getDefaultLocaleLabel(): string;

    public function getShortnamesSimpleArray(): array;

    public function getNamesSimpleArray(): array;

    public function getLabelsSimpleArray(): array;

    public function getShortnameExists(string $shortname): bool;
}
