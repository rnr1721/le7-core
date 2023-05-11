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

    private ?string $defController = null;
    private ?string $defAction = null;
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

        $this->defAction = $this->config->string('defaultAction', 'index') ?? 'index';
        $this->defController = $this->config->string('defaultController', 'index') ?? 'index';

        $this->base = $request->getBaseUrl();
    }

    /**
     * Get link to some internal page
     * @param string $location Example: page/contacts
     * @param string $params Example: ?param1=one&param2=two
     * @param string $route Example: admin
     * @param string $language current language. If empty - will be default
     * @return string
     */
    public function get(string $location = '', string $params = '', string $route = '', string $language = ''): string
    {
        $data = array_filter(explode('/', $location));
        $controllerRaw = $data[0] ?? $this->defController;
        $actionRaw = $data[1] ?? $this->defAction;

        /** @var string $controller */
        $controller = $controllerRaw !== $this->defController ? $controllerRaw : '';
        /** @var string $action */
        $action = $actionRaw !== $this->defAction ? $actionRaw : '';

        $languageCurrent = $language ?: $this->locales->getCurrentLocaleShortname();
        $defLanguage = $this->config->string('defaultLanguage', 'en') ?? 'en';
        $lang = $languageCurrent === $defLanguage ? '' : $languageCurrent;

        $route = '/' . trim($route, '/');
        $result = '/' . ($lang === '' ? '' : $lang . '/') . $controller . '/' . $action;
        $all = trim($route . $result, '/');
        $url = rtrim($this->base . '/' . $all, '/');
        return $url . ($params ? $params : '');
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
        return $this->base;
    }

}
