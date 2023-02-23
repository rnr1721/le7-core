<?php

declare(strict_types=1);

namespace le7\Core\Locales;

use le7\Core\Config\ConfigInterface;
use le7\Core\Config\TopologyFsInterface;

class Locales implements LocalesInterface
{

    protected ConfigInterface $config;
    protected TopologyFsInterface $topology;

    protected string $defaultLocaleName;
    protected string $defaultLocaleShortname;
    protected string $defaultLocaleLabel;
    protected string $currentLocaleName;
    protected string $currentLocaleShortname;
    protected string $currentLocaleLabel;
    protected array $localesByName;
    protected array $localesByShortname;

    public function __construct(ConfigInterface $config, TopologyFsInterface $topology)
    {

        $this->config = $config;
        $this->topology = $topology;

        $this->localesByShortname = $config->getLocales();

        $localesByName = array();
        foreach ($this->localesByShortname as $language => $languageData) {
            $current = array(
                'shortname' => $language,
                'label' => $languageData['label']
            );
            $localesByName[$languageData['name']] = $current;
        }
        $this->localesByName = $localesByName;

        $this->defaultLocaleName = $this->getLocaleNameByShortname($config->getDefaultLanguage());
        $this->defaultLocaleShortname = $config->getDefaultLanguage();
        $this->defaultLocaleLabel = $this->getLocaleLabelByShortname($config->getDefaultLanguage());

        //if (empty($currentLocaleShortname) || !array_key_exists($currentLocaleShortname,$this->localesByShortname)) {
        //    $currentLocaleShortname = $this->getDefaultLocaleShortname();
        //}

        //$this->setLocale($currentLocaleShortname);

    }

    /**
     * Установка системной локали по системному имени локали
     * @param string $localeShortname
     * @return bool
     */
    public function setLocale(string $localeShortname) : bool {
        if (array_key_exists($localeShortname,$this->localesByShortname)) {
            $result = true;
        } else {
            $localeShortname = $this->getDefaultLocaleShortname();
            $result = false;
        }
        $locale = $this->getLocaleNameByShortname($localeShortname);
        putenv("LANG=$locale");
        putenv("LC_ALL={$locale}");
        setlocale(LC_ALL, $locale.'.utf8');
        $domain = 'le7_'.$locale;
        bindtextdomain($domain, $this->topology->getLocalesPath());
        textdomain($domain);
        bind_textdomain_codeset($domain, 'UTF-8');
        $this->currentLocaleName = $locale;
        $this->currentLocaleShortname = $localeShortname;
        $this->currentLocaleLabel = $this->getLocaleLabelByName($locale);
        return $result;
    }

    /**
     * Получение всех данных локали по её системному имени
     * @param string $localeName
     * @return array
     */
    public function getLocaleByName(string $localeName): array {
        return $this->localesByName[$localeName];
    }

    /**
     * Получение человекочитаемого имени локали, например "Русский" по системному имени локали
     * @param string $localeName
     * @return string
     */
    public function getLocaleLabelByName(string $localeName) : string {
        return $this->localesByName[$localeName]['label'];
    }

    /**
     * Получение краткого имени локали, например "ru" по системному имени локали
     * @param string $localeName
     * @return string
     */
    public function getLocaleShortnameByName(string $localeName) : string {
        return $this->localesByName[$localeName]['shortname'];
    }

    /**
     * Получение всех данных локали по её краткому имени
     * @param string $localeShortname
     * @return array
     */
    public function getLocaleByShortname(string $localeShortname): array {
        return $this->localesByShortname[$localeShortname];
    }

    /**
     * Получение человекочитаемого имени локали, например "Русский" по краткому имени локали
     * @param string $localeShortname
     * @return string
     */
    public function getLocaleLabelByShortname(string $localeShortname) : string {
        return $this->localesByShortname[$localeShortname]['label'];
    }

    /**
     * Получение системного имени локали, например "ru_RU" по краткому имени локали
     * @param string $localeShortname
     * @return string
     */
    public function getLocaleNameByShortname(string $localeShortname) : string {
        return $this->localesByShortname[$localeShortname]['name'];
    }

    /**
     * Получение массива из всех локалей, где ключ - системное имя локали
     * @return array
     */
    public function getLocalesByName() : array {
        return $this->localesByName;
    }

    /**
     * Получение массива из всех локалей, где ключ - краткое имя локали
     * @return array
     */
    public function getLocalesByShortname() : array {
        return $this->localesByShortname;
    }

    /**
     * Получение системного имени текущей локали
     * @return string
     */
    public function getCurrentLocaleName() : string {
        return  $this->currentLocaleName;
    }

    /**
     * Получение краткого имени текущей локали
     * @return string
     */
    public function getCurrentLocaleShortname() : string {
        return $this->currentLocaleShortname;
    }

    /**
     * Получение человекочитаемого имени текущей локали
     * @return string
     */
    public function getCurrentLocaleLabel() : string {
        return $this->currentLocaleLabel;
    }

    /**
     * Возвращает системное имя локали по умолчанию
     * @return string
     */
    public function getDefaultLocaleName(): string
    {
        return $this->defaultLocaleName;
    }

    /**
     * Возвращает короткое имя локали по умолчанию
     * @return string
     */
    public function getDefaultLocaleShortname(): string
    {
        return $this->defaultLocaleShortname;
    }

    /**
     * Возвращает человекочитаемое имя локали по умолчанию
     * @return string
     */
    public function getDefaultLocaleLabel(): string
    {
        return $this->defaultLocaleLabel;
    }

    /**
     * Возвращает обычный массив с короткими названиями языков
     * @return array
     */
    public function getShortnamesSimpleArray() : array {
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
    public function getNamesSimpleArray() : array {
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
    public function getLabelsSimpleArray() : array {
        $result = array();
        foreach ($this->localesByShortname as $item) {
            $result[] = $item['label'];
        }
        return $result;
    }

    public function getShortnameExists(string $shortname) : bool {
        return array_key_exists($shortname, $this->localesByShortname);
    }
    
}
