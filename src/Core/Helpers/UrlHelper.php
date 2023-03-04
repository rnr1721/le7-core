<?php

namespace App\Core\Helpers;

use App\Core\Instances\RouteHttpInterface;
use App\Core\Request\Request;
use App\Core\Locales\LocalesInterface;
use App\Core\Config\TopologyPublicInterface;
use App\Core\Config\ConfigInterface;

class UrlHelper {

    private Request $request;
    private LocalesInterface $locales;
    private TopologyPublicInterface $topologyWeb;
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config, TopologyPublicInterface $topologyWeb, LocalesInterface $locales, Request $request) {
        $this->config = $config;
        $this->topologyWeb = $topologyWeb;
        $this->locales = $locales;
        $this->request = $request;
    }

    public function get(string $controller = '', string $action = '', string $params = '', string $route = '', string $language = ''): string {
        $result = '';
        $defController = $this->config->getDefaultController();
        $defAction = $this->config->getDefaultAction();
        $defLanguage = $this->config->getDefaultLanguage();
        if ($controller === $defController) {
            $controller = '';
        }
        if ($action === $defAction) {
            $action = '';
        }
        if (empty($language)) {
            $language = $this->locales->getCurrentLocaleShortname();
        }
        if ($language === $defLanguage) {
            $language = '';
        }

        $result = '/' . $language . '/' . $controller . '/' . $action;

        if (!empty($route)) {
            $route = '/' . $route;
        }

        $all = str_replace('//', '/', $route . $result);

        $url = rtrim($this->topologyWeb->getBaseUrl() . $all, '/');
        return $url . (empty($params) ? '' : $params);
    }

    public function __toString(): string {
        return $this->topologyWeb->getBaseUrl();
    }

    /**
     * Return array of links for current page in other languages
     * @return array
     */
    public function getLanguageUrlVariants(RouteHttpInterface $currentRoute): array {
        $uri = $this->request->getUri();
        $base = $this->topologyWeb->getBaseUrl() . $currentRoute->getBaseRoot();

        $defaultLocale = $this->locales->getDefaultLocaleShortname();
        $currentLocale = $this->locales->getCurrentLocaleShortname();

        $cleanUri = '/' . ltrim(str_replace($base, '', (string) $uri), '/');

        $result = [];
        foreach ($this->locales->getLocalesByShortname() as $locale => $value) {
            if ($locale !== $this->locales->getCurrentLocaleShortname()) {

                if ($locale === $defaultLocale) {
                    $locale = '';
                } else {
                    $locale = '/' . $locale;
                }

                $newbase = str_replace('/' . $currentLocale, $locale, rtrim($currentRoute->getBaseRoot(), '/'));

                if ($currentLocale === $defaultLocale) {
                    $newbase = $newbase . $locale;
                }

                $newurl = $this->topologyWeb->getBaseUrl() . $newbase;

                $result[] = [
                    'url' => $newurl . $cleanUri,
                    'label' => $value['label']
                ];
            }
        }
        return $result;
    }

}
