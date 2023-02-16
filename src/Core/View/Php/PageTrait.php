<?php

declare(strict_types=1);

namespace le7\Core\View\Php;

trait PageTrait {

    private array $jsImportMap = array(
        'imports' => []
    );

    /**
     * Set data for microformatting
     * @param string $jsonMicroformat
     * @return ControllerWeb
     */
    public function setMicroFormatting(string $jsonMicroformat): self {
        $this->vars['microformat'] = $jsonMicroformat;
        return $this;
    }

    /**
     * Set page keywords for $vars ($keywords var)
     * @param string|array $keywords Comma-separated keywords or array with keywords
     * @return ControllerWeb
     */
    public function setPageKeywords(string|array $keywords): self {
        if (is_array($keywords)) {
            $keywords = implode(',', $keywords);
        }
        $this->vars['keywords'] = $keywords;
        return $this;
    }

    /**
     * Set page title for $vars ($title var)
     * @param string $pageTitle Title of page
     * @return $this
     */
    public function setPageTitle(string $pageTitle): self {
        $this->vars['title'] = $pageTitle;
        return $this;
    }

    /**
     * Set page head for H1 tag for $vars ($header var)
     * @param string $pageHeader Header content
     * @return $this
     */
    public function setPageHeader(string $pageHeader): self {
        $this->vars['header'] = $pageHeader;
        return $this;
    }

    /**
     * Set page description, that available in $vars ($description)
     * @param string $description
     * @return ControllerWeb
     */
    public function setPageDescription(string $description): self {
        $this->vars['description'] = $description;
        return $this;
    }

    /**
     * Generate JS importmap from array [$key=>$value]
     * @param array $vars
     * @param bool $internal
     * @param string $type
     * @return $this
     */
    public function setImportMap(array $vars, bool $internal = true, string $type = "importmap"): self {
        foreach ($vars as $oneVar => $varValue) {
            if ($internal) {
                $a = $this->topologyWeb->getLibsUrl() . '/' . $varValue;
            } else {
                $a = $varValue;
            }
            $this->jsImportMap['imports'][$oneVar] = $a;
        }
        $template = '<script type="' . $type . '">' . PHP_EOL . '{data}' . PHP_EOL . '</script>';

        $dataString = json_encode($this->jsImportMap, JSON_PRETTY_PRINT);

        $result = str_replace('{data}', $dataString, $template);

        $this->vars['importmap'] = $result;
        return $this;
    }

    /**
     * Set external JS script
     * Example: setScriptCdn("http://cdn.net/jquery-latest/jquery.js");
     * @param string $address
     * @param bool $header
     * @param string $params
     */
    public function setScriptCdn(string $address, bool $header = true, string $params = ''): self {
        if (!empty($params)) {
            $params = ' ' . $params;
        }
        if ($header) {
            $scripts_place = 'scripts_header';
        } else {
            $scripts_place = 'scripts_footer';
        }
        $string = '<script' . $params . ' src="' . $address . '"></script>' . "\r\n";
        if (isset($this->vars[$scripts_place])) {
            $this->vars[$scripts_place] .= $string;
        } else {
            $this->vars[$scripts_place] = $string;
        }
        return $this;
    }

    /**
     * Get JS script from theme js dir {PUBLIC}/themes/{THEME}/js
     * Example: setScript('myjs.js');
     * Result: https://site.com/themes/yourtheme/js/myjs.js
     * @param string $scriptName Filename of script
     * @param bool $header If true, scripts put in header, id false - in footer ($scripts_header and $scripts_footer vars)
     * @param string $params Params in <script> tag, for example "defer" or type="module"
     * @param string $version Set ?v=$version after script URL
     * @return self
     */
    public function setScript(string $scriptName, bool $header = true, string $params = '', string $version = ''): self {
        if (!empty($version)) {
            $version = '?v=' . $version;
        }
        $url = $this->topologyWeb->getJsUrl() . '/' . $scriptName . $version;
        $this->setScriptCdn($url, $header, $params);
        return $this;
    }

    /**
     * Get JS script from public lib dir {PUBLIC}/libs
     * Example: setScriptLib('folder/myjs.js');
     * Result: https://site.com/libs/folder/myjs.js
     * @param string $scriptName Filename of script
     * @param bool $header If true, scripts put in header, id false - in footer ($scripts_header and $scripts_footer vars)
     * @param string $params Params in <script> tag, for example "defer" or type="module"
     * @return self
     */
    public function setScriptLib(string $scriptName, bool $header = true, string $params = ''): self {
        $url = $this->topologyWeb->getLibsUrl() . '/' . $scriptName;
        $this->setScriptCdn($url, $header, $params);
        return $this;
    }

    /**
     * Plug any CSS style by URL
     * @param string $url Url of script in CDN or local
     * @return self
     */
    public function setStyleCdn(string $url): self {
        $start = '<link rel="stylesheet" href="';
        $end = '">' . "\r\n";
        if (isset($this->vars['styles'])) {
            $this->vars['styles'] .= $start . $url . $end;
        } else {
            $this->vars['styles'] = $start . $url . $end;
        }
        return $this;
    }

    /**
     * Plug CSS style in theme folder {PUBLIC}/themes/{THEME}/css
     * @param string $styleName Filename of style
     * @param string $version Set ?v=$version after css URL
     * @return self
     */
    public function setStyle(string $styleName, string $version = ''): self {
        if (!empty($version)) {
            $version = '?v=' . $version;
        }
        $url = $this->topologyWeb->getCssUrl() . '/' . $styleName . $version;
        $this->setStyleCdn($url);
        return $this;
    }

    /**
     * Plug CSS style from libs dir in public folder {PUBLIC}/libs
     * Example: setStyleLib('css/mycss.css');
     * @param string $styleName Filename of style
     * @return self
     */
    public function setStyleLib(string $styleName): self {
        $url = $this->topologyWeb->getLibsUrl() . '/' . $styleName;
        $this->setStyleCdn($url);
        return $this;
    }

}
