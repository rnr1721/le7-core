<?php

declare(strict_types=1);

namespace Core\Locales;

use Core\Interfaces\LocalesInterface;
use Core\Interfaces\ConfigInterface;

class LocalesDefault implements LocalesInterface
{

    protected ConfigInterface $config;
    protected string $defaultLocaleName = 'en_US';
    protected string $defaultLocaleShortname = 'en';
    protected string $defaultLocaleLabel = 'English';
    protected string $currentLocaleName = 'en_US';
    protected string $currentLocaleShortname = 'en';
    protected string $currentLocaleLabel = 'English';
    protected array $localesByName;
    protected array $localesByShortname;

    public function __construct(ConfigInterface $config)
    {

        $this->config = $config;

        $this->localesByShortname = $config->array('locales') ?? [];

        $localesByName = [];
        foreach ($this->localesByShortname as $language => $languageData) {
            $current = [
                'shortname' => $language,
                'label' => $languageData['label']
            ];
            $localesByName[$languageData['name']] = $current;
        }
        $this->localesByName = $localesByName;

        $defaultLanguage = $config->string('defaultLanguage') ?? 'en';

        $this->defaultLocaleName = $this->getLocaleNameByShortname($defaultLanguage);
        $this->defaultLocaleShortname = $defaultLanguage;
        $this->defaultLocaleLabel = $this->getLocaleLabelByShortname($defaultLanguage);
    }

    /**
     * Установка системной локали по системному имени локали
     * @param string $localeShortname
     * @return bool
     */
    public function setLocale(string $localeShortname): bool
    {
        if (array_key_exists($localeShortname, $this->localesByShortname)) {
            $result = true;
        } else {
            $localeShortname = $this->getDefaultLocaleShortname();
            $result = false;
        }
        $locale = $this->getLocaleNameByShortname($localeShortname);
        putenv("LANG=$locale");
        putenv("LC_ALL={$locale}");
        setlocale(LC_ALL, $locale . '.utf8');
        $domain = 'le7_' . $locale;
        bindtextdomain($domain, $this->config->string('loc.locales'));
        textdomain($domain);
        bind_textdomain_codeset($domain, 'UTF-8');
        $this->currentLocaleName = $locale;
        $this->currentLocaleShortname = $localeShortname;
        $this->currentLocaleLabel = $this->getLocaleLabelByName($locale);
        return $result;
    }

    /**
     * add locale from plugin
     * 
     * @param string $textdomain Textdomain
     * @param string $path Locales path
     * @return self
     */
    public function addLocale(string $textdomain, string $path): self
    {
        bindtextdomain($textdomain, $path);
        textdomain($textdomain);
        return $this;
    }

    /**
     * Получение всех данных локали по её системному имени
     * @param string $localeName
     * @return array
     */
    public function getLocaleByName(string $localeName): array
    {
        return $this->localesByName[$localeName];
    }

    /**
     * Получение человекочитаемого имени локали, например "Русский" по системному имени локали
     * @param string $localeName
     * @return string
     */
    public function getLocaleLabelByName(string $localeName): string
    {
        return $this->localesByName[$localeName]['label'];
    }

    /**
     * Получение краткого имени локали, например "ru" по системному имени локали
     * @param string $localeName
     * @return string
     */
    public function getLocaleShortnameByName(string $localeName): string
    {
        return $this->localesByName[$localeName]['shortname'];
    }

    /**
     * Получение всех данных локали по её краткому имени
     * @param string $localeShortname
     * @return array
     */
    public function getLocaleByShortname(string $localeShortname): array
    {
        return $this->localesByShortname[$localeShortname];
    }

    /**
     * Получение человекочитаемого имени локали, например "Русский" по краткому имени локали
     * @param string $localeShortname
     * @return string
     */
    public function getLocaleLabelByShortname(string $localeShortname): string
    {
        return $this->localesByShortname[$localeShortname]['label'];
    }

    /**
     * Получение системного имени локали, например "ru_RU" по краткому имени локали
     * @param string $localeShortname
     * @return string
     */
    public function getLocaleNameByShortname(string $localeShortname): string
    {
        return $this->localesByShortname[$localeShortname]['name'];
    }

    public function getLocalesByName(): array
    {
        return $this->localesByName;
    }

    public function getLocalesByShortname(): array
    {
        return $this->localesByShortname;
    }

    public function getCurrentLocaleName(): string
    {
        return $this->currentLocaleName;
    }

    public function getCurrentLocaleShortname(): string
    {
        return $this->currentLocaleShortname;
    }

    public function getCurrentLocaleLabel(): string
    {
        return $this->currentLocaleLabel;
    }

    public function getDefaultLocaleName(): string
    {
        return $this->defaultLocaleName;
    }

    public function getDefaultLocaleShortname(): string
    {
        return $this->defaultLocaleShortname;
    }

    public function getDefaultLocaleLabel(): string
    {
        return $this->defaultLocaleLabel;
    }

    /**
     * Возвращает обычный массив с короткими названиями языков
     * @return array
     */
    public function getShortnamesSimpleArray(): array
    {
        $result = array();
        foreach ($this->localesByName as $item) {
            $result[] = $item['shortname'];
        }
        return $result;
    }

    /**
     * Возвращает обычный массив с системными названиями языков
     * @return array
     */
    public function getNamesSimpleArray(): array
    {
        $result = array();
        foreach ($this->localesByShortname as $item) {
            $result[] = $item['name'];
        }
        return $result;
    }

    /**
     * Возвращает обычный массив с человекочитаемыми названиями языков
     * @return array
     */
    public function getLabelsSimpleArray(): array
    {
        $result = array();
        foreach ($this->localesByShortname as $item) {
            $result[] = $item['label'];
        }
        return $result;
    }

    public function getShortnameExists(string $shortname): bool
    {
        return array_key_exists($shortname, $this->localesByShortname);
    }
}
