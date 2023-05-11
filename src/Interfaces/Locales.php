<?php

declare(strict_types=1);

namespace Core\Interfaces;

interface Locales
{

    /**
     * Set current locale
     * @param string $localeShortname Locale shortname
     * @return bool
     */
    public function setLocale(string $localeShortname): bool;

    /**
     * Get locale by name e.g. ru_RU
     * @param string $localeName
     * @return array
     */
    public function getLocaleByName(string $localeName): array;

    /**
     * Get locale label by name
     * @param string $localeName Locale name
     * @return string
     */
    public function getLocaleLabelByName(string $localeName): string;

    /**
     * Get locale shortname by name
     * @param string $localeName Locale name
     * @return string
     */
    public function getLocaleShortnameByName(string $localeName): string;

    /**
     * Get locale name and label by shortname
     * @param string $localeShortname Locale shortname
     * @return array
     */
    public function getLocaleByShortname(string $localeShortname): array;

    /**
     * Get locale label by shortname
     * @param string $localeShortname Locale shortname
     * @return string
     */
    public function getLocaleLabelByShortname(string $localeShortname): string;

    /**
     * Get locale name by shortname
     * @param string $localeShortname Locale shortname
     * @return string
     */
    public function getLocaleNameByShortname(string $localeShortname): string;

    /**
     * Get all locales with key "name"
     * @return array<array-key, array>
     */
    public function getLocalesByName(): array;

    /**
     * Get all locales with key "shortname"
     * @return array<array-key, array>
     */
    public function getLocalesByShortname(): array;

    /**
     * Get name of current locale
     * @return string
     */
    public function getCurrentLocaleName(): string;

    /**
     * Get shortname of current locale
     * @return string
     */
    public function getCurrentLocaleShortname(): string;

    /**
     * Get lebel of current locale
     * @return string
     */
    public function getCurrentLocaleLabel(): string;

    /**
     * Get name of default locale
     * @return string
     */
    public function getDefaultLocaleName(): string;

    /**
     * Get shortname of default locale
     * @return string
     */
    public function getDefaultLocaleShortname(): string;

    /**
     * Get label of default locale
     * @return string
     */
    public function getDefaultLocaleLabel(): string;

    /**
     * Get locale shortnames as array
     * @return array
     */
    public function getShortnamesSimpleArray(): array;

    /**
     * Get locale names as array
     * @return array
     */
    public function getNamesSimpleArray(): array;

    /**
     * Get locale labels as array
     * @return array
     */
    public function getLabelsSimpleArray(): array;

    /**
     * Get is locale by shortname exists
     * @param string $shortname Locale shortname
     * @return bool
     */
    public function getShortnameExists(string $shortname): bool;
}
