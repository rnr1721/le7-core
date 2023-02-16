<?php

declare(strict_types=1);

namespace le7\Core\Locales;

use le7\Core\Helpers\StringHelper;

class Translate
{

    private StringHelper $stringHelper;
    private Locales $locales;

    public function __construct(Locales $locales, StringHelper $stringHelper) {
        $this->stringHelper = $stringHelper;
        $this->locales = $locales;
    }

    /**
     * @param string|array $var
     * @param string $lang
     * @param string $returnIfEmpty
     * @param bool $reportEmpty
     * @return string
     */
    public function get(string|array $var, string $lang='', string $returnIfEmpty = 'en', bool $reportEmpty = false) : string {
        if (empty($lang)) {
            $lang = $this->locales->getDefaultLocaleShortname();
        }
        if ($this->stringHelper->isJson($var)) {
            $output = json_decode($var,true);
            if ($reportEmpty) {
                if (!isset($output[$lang])) {
                    return '';
                }
            }
            if (isset($output[$lang])) {
                $result = $output[$lang];
            } else {
                if (!empty($returnIfEmpty)) {
                    if (isset($output[$returnIfEmpty])) {
                        $result = $output[$returnIfEmpty];
                    } else {
                        return '';
                    }
                } else {
                    if (isset($output[$this->locales->getCurrentLocaleShortname()])) {
                        $result = $output[$this->locales->getCurrentLocaleShortname()];
                    } else {
                        return '';
                    }
                }
            }
            if ($result) {
                return $result;
            }
            return '';
        }
        return $var;
    }

    /**
     * @param string $data
     * @param string|array $var
     * @param string $lang
     * @return string
     */
    public function set(string $data='', string|array $var='', string $lang='') : string {
        if (empty($lang)) {
            $lang = $this->locales->getCurrentLocaleShortname();
        }
        $result = array();
        if (!empty($data)) {
            if (!empty($var)) {
                if (is_array($var)) {
                    $result = $var;
                } else {
                    if ($this->stringHelper->isJson($var)) {
                        $result = json_decode($var, true);
                    } else {
                        $result = $this->getAsArray(htmlentities($var));
                    }
                }
                $result[$lang] = htmlentities($data);
            }
            if ($result) {
                return json_encode($result,JSON_UNESCAPED_UNICODE);
            }
                return '';
        } else {
            if (empty($var)) {
                return '';
            }
            return $var;
        }
    }

    /**
     * Получает все строки перевода на разные языки в виде массива
     * и возвращает массив. Если на входе не JSON а строка то возвращает массив где она на дефолтном языке
     * @param string $value
     * @return array
     */
    public function getAsArray(string $value='') : array {
        if ($this->stringHelper->isJson($value)) {
            return json_decode($value);
        } else {
            $result = array();
            $result[$this->locales->getDefaultLocaleShortname()] = $value;
            return $result;
        }
    }

    /**
     * Возвращает результат попытки перевода поля базы данных
     *
     * На вход подается текст или JSON и в случае успеха вернет или
     * строку на нужном языке или просто String
     *
     * @param string $fieldName
     * @param string $value
     * @param string $lang
     * @return string
     */
    public function getDbField(string $fieldName='', string $value='', string $lang='') : string
    {
        if (stripos($fieldName, '_int') !== false)
        {
            return html_entity_decode($this->get($value,$lang));
        }
        return $value;
    }

    /**
     * @param string $value
     * @return array
     */
    public function getUntranslated(string $value) : array {
        $result = array();
        $all_languages = $this->locales->getShortnamesSimpleArray();
        $current_languages = $this->getAsArray($value);
        foreach ($all_languages as $language) {
            if (!isset($current_languages->{$language})) {
                $result[$language] = $language;
            }
        }
        return $result;
    }

    public function transliterate(string $textcyr='') : string {
        $cyr  = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у',
            'ф','х','ц','ч','ш','щ','ъ', 'ы','ь', 'э', 'ю','я','А','Б','В','Г','Д','Е','Ж',
            'З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У',
            'Ф','Х','Ц','Ч','Ш','Щ','Ъ', 'Ы','Ь', 'Э', 'Ю','Я' );
        $lat = array('a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p','r','s','t','u',
            'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'', 'i', '', 'e' ,'yu' ,'ya','A','B','V','G','D','E','Zh',
            'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
            'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht','' ,'A' ,'`' ,'E','Yu' ,'Ya');
        return str_replace($cyr, $lat, $textcyr);
    }

}
