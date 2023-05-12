<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Interfaces\Url;
use Core\Interfaces\RouteHttp;
use Core\Interfaces\Request;
use Core\Interfaces\Locales;
use Core\Interfaces\Config;

class UrlBuilder implements Url
{

    private Request $request;
    private Locales $locales;
    private Config $config;
    private RouteHttp $route;
    private string $base;

    public function __construct(Config $config, Locales $locales, Request $request, RouteHttp $route)
    {
        $this->config = $config;
        $this->locales = $locales;
        $this->request = $request;
        $this->route = $route;

        $this->base = $request->getBaseUrl();
    }

    /**
     * Get link to some internal page
     * @param string|null $location Example: page/contacts
     * @param string|array|null $params Example: ?param1=one&param2=two
     * @param string|null $language current language. If null - will be default
     * @return string
     */
    public function get(
            string|null $location = null,
            string|array|null $params = null,
            string|null $language = null
    ): string
    {

        if ($location === null) {
            $location = '';
        }
        $paramsString = is_string($params) ? $params : '';

        if (is_array($params)) {
            $parameters = '';
            foreach ($params as $paramKey => $paramValue) {
                $parameters .= '&' . $paramKey . '=' . $paramValue;
            }
            $paramsString = ltrim($parameters, '&');
        }

        $languageCurrent = $language ?? $this->locales->getCurrentLocaleShortname();
        $defLanguage = $this->locales->getDefaultLocaleShortname();
        $lang = $languageCurrent === $defLanguage ? '' : '/' . $languageCurrent;

        $clocation = $location === '' ? '' : '/' . trim($location, '/');

        return $this->base . $lang . rtrim($clocation, '/') . $paramsString;
    }

    public function theme(): string
    {
        $theme = $this->config->string('theme') ?? "main";
        return $this->base . '/themes/' . $theme . '/';
    }

    public function libs(): string
    {
        return $this->base . '/libs';
    }

    public function js(): string
    {
        return $this->theme() . 'js';
    }

    public function css(): string
    {
        return $this->theme() . 'css';
    }

    public function fonts(): string
    {
        return $this->theme() . 'fonts';
    }

    public function images(): string
    {
        return $this->theme() . 'images';
    }

    /**
     * Return array of links for current page in other languages
     * except default language
     * @param RouteHttp|null $currentRoute
     * @return array
     */
    public function getLanguageUrlVariants(?RouteHttp $currentRoute = null): array
    {

        if ($currentRoute === null) {
            $currentRoute = $this->route;
        }

        $uri = $this->request->getUri();

        $base = $currentRoute->getBaseRoot();
        $fullBase = $this->base . $base;

        $defaultLocale = $this->locales->getDefaultLocaleShortname();
        $currentLocale = $this->locales->getCurrentLocaleShortname();

        $cleanUri = '/' . ltrim(str_replace($fullBase, '', (string) $uri), '/');

        $result = [];
        foreach ($this->locales->getLocalesByShortname() as $locale => $value) {

            if ($locale === $currentLocale) {
                continue;
            }

            $localePath = ($locale === $defaultLocale ? '' : '/' . $locale);

            $newbase = str_replace('/' . $currentLocale, $localePath, rtrim($base, '/'));

            if ($currentLocale === $defaultLocale) {
                $newbase = $newbase . $localePath;
            }

            $newurl = $this->base . $newbase;

            $result[] = [
                'url' => $newurl . ($cleanUri === '/' ? '' : $cleanUri),
                'label' => $value['label']
            ];
        }

        return $result;
    }

    public function __toString(): string
    {
        return $this->get();
    }

}
