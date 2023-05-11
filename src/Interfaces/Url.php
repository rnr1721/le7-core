<?php

declare(strict_types=1);

namespace Core\Interfaces;

use Stringable;

interface Url extends Stringable
{

    /**
     * Get link to some internal page. If all parameters empty - get base link
     * @param string $location Example: page/contacts
     * @param string $params Example: ?param1=one&param2=two
     * @param string $route Example: admin
     * @param string $language current language. If empty - will be default
     * @return string
     */
    public function get(string $location = '', string $params = '', string $route = '', string $language = ''): string;

    /**
     * Get theme URL
     * @return string
     */
    public function theme(): string;

    /**
     * Get global public libs URL
     * @return string
     */
    public function libs(): string;

    /**
     * Get theme JS URL
     * @return string
     */
    public function js(): string;

    /**
     * Get theme CSS URL
     * @return string
     */
    public function css(): string;

    /**
     * Get theme fonts URL
     * @return string
     */
    public function fonts(): string;

    /**
     * Get theme images URL
     * @return string
     */
    public function images(): string;

    /**
     * Get all language links for current page except current lang
     * @param RouteHttp $currentRoute Current route
     * @return array
     */
    public function getLanguageUrlVariants(RouteHttp $currentRoute): array;

    public function __toString(): string;
}
