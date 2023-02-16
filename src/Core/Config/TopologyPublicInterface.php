<?php

namespace le7\Core\Config;

interface TopologyPublicInterface {

    /**
     * Get base URL, for example https://example.com
     * can be returned wit subfolder,
     * if public dir placed in subfolder
     * @return string
     */
    public function getBaseUrl(): string;

    /**
     * Get https://example.com/libs URL
     * for CSS, JS and other libraries
     * @return string
     */
    public function getLibsUrl(): string;

    /**
     * Get url for https://site.com/themes
     * @return string
     */
    public function getThemesUrl(): string;

    /**
     * Get URL for https://site.com/themes/theme
     * @return string
     */
    public function getThemeUrl(): string;

    /**
     * Get URL for https://site.com/themes/theme/css
     * @return string
     */
    public function getCssUrl(): string;

    /**
     * Get URL for https://site.com/themes/theme/js
     * @return string
     */
    public function getJsUrl(): string;

    /**
     * Get url for https://site.com/uploads
     * @return string
     */
    public function getUploadUrl(): string;

    /**
     * Get URL for https://site.com/themes/theme/fonts
     * @return string
     */
    public function getFontsUrl(): string;

    /**
     * Get URL for https://site.com/themes/theme/images
     * @return string
     */
    public function getImagesUrl(): string;
}
