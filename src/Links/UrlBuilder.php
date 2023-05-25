<?php

declare(strict_types=1);

namespace Core\Links;

use Core\Interfaces\UrlInterface;
use Core\Interfaces\RouteHttpInterface;
use Core\Interfaces\RequestInterface;
use Core\Interfaces\LocalesInterface;
use Core\Interfaces\ConfigInterface;

/**
 * Class for building links
 */
class UrlBuilder implements UrlInterface
{

    /**
     * System request object
     * @var RequestInterface
     */
    private RequestInterface $request;
    
    /**
     * Locales
     * @var LocalesInterface
     */
    private LocalesInterface $locales;
    
    /**
     * Config storage
     * @var ConfigInterface
     */
    private ConfigInterface $config;
    
    /**
     * Current route
     * @var RouteHttpInterface
     */
    private RouteHttpInterface $route;
    
    /**
     * Base url
     * @var string
     */
    private string $base;

    /**
     * URL builder Constructor
     * @param ConfigInterface $config
     * @param LocalesInterface $locales
     * @param RequestInterface $request
     * @param RouteHttpInterface $route
     */
    public function __construct(
            ConfigInterface $config,
            LocalesInterface $locales,
            RequestInterface $request,
            RouteHttpInterface $route
    )
    {
        $this->config = $config;
        $this->locales = $locales;
        $this->request = $request;
        $this->route = $route;

        $this->base = $request->getBaseUrl();
    }

    /**
     * @inheritDoc
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
        $paramsString = is_string($params) ? '?' . $params : '';

        if (is_array($params) && count($params) !== 0) {
            $parameters = '';
            foreach ($params as $paramKey => $paramValue) {
                $parameters .= '&' . $paramKey . '=' . $paramValue;
            }
            $paramsString = '?' . ltrim($parameters, '&');
        }

        $languageCurrent = $language ?? $this->locales->getCurrentLocaleShortname();
        $defLanguage = $this->locales->getDefaultLocaleShortname();
        $lang = $languageCurrent === $defLanguage ? '' : '/' . $languageCurrent;

        $clocation = $location === '' ? '' : '/' . trim($location, '/');

        return $this->base . $lang . rtrim($clocation, '/') . $paramsString;
    }

    /**
     * @inheritDoc
     */
    public function theme(): string
    {
        $theme = $this->config->string('theme') ?? "main";
        return $this->base . '/themes/' . $theme . '/';
    }

    /**
     * @inheritDoc
     */
    public function libs(): string
    {
        return $this->base . '/libs';
    }

    /**
     * @inheritDoc
     */
    public function js(): string
    {
        return $this->theme() . 'js';
    }

    /**
     * @inheritDoc
     */
    public function css(): string
    {
        return $this->theme() . 'css';
    }

    /**
     * @inheritDoc
     */
    public function fonts(): string
    {
        return $this->theme() . 'fonts';
    }

    /**
     * @inheritDoc
     */
    public function images(): string
    {
        return $this->theme() . 'images';
    }

    /**
     * @inheritDoc
     */
    public function getLanguageUrlVariants(
            ?RouteHttpInterface $currentRoute = null
    ): array
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
