<?php

namespace App\Core\Config;

use App\Core\Request\Request;

class TopologyPublic implements TopologyPublicInterface {

    private ConfigInterface $config;
    private Request $request;
    private array $topology = array(
        'baseUrl' => '{baseurl}',
        'libsUrl' => '{baseurl}/libs',
        'themesUrl' => '{baseurl}/themes',
        'themeUrl' => '{baseurl}/themes/{theme}',
        'cssUrl' => '{baseurl}/themes/{theme}/css',
        'jsUrl' => '{baseurl}/themes/{theme}/js',
        'fontsUrl' => '{baseurl}/themes/{theme}/fonts',
        'imagesUrl' => '{baseurl}/themes/{theme}/images',
        'uploadsUrl' => '{baseurl}/uploads',
    );

    public function __construct(ConfigInterface $config, Request $request) {
        $this->config = $config;
        $this->request = $request;
        $portFromUri = $this->request->getUri()->getPort();
        $port = (empty($portFromUri) ? '' : ':'.$portFromUri);
        $baseUrl = $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . $port . $this->request->getBase();
        foreach ($this->topology as &$item) {
            //$item = str_replace('{baseurl}', $this->config->getUrl(), $item);
            //$item = str_replace('{theme}', $this->config->getTheme(), $item);
            $search = array('{baseurl}', '{theme}');
            $replace = array(rtrim($baseUrl,'/'), $config->getTheme());
            $item = str_replace($search, $replace, $item);
        }
    }

    public function getBaseUrl(): string {
        return $this->topology['baseUrl'];
    }

    public function getLibsUrl(): string {
        return $this->topology['libsUrl'];
    }

    public function getThemesUrl(): string {
        return $this->topology['themesUrl'];
    }

    public function getThemeUrl(): string {
        return $this->topology['themeUrl'];
    }

    public function getCssUrl(): string {
        return $this->topology['cssUrl'];
    }

    public function getJsUrl(): string {
        return $this->topology['jsUrl'];
    }

    public function getUploadUrl(): string {
        return $this->topology['uploadsUrl'];
    }

    public function getFontsUrl(): string {
        return $this->topology['fontsUrl'];
    }

    public function getImagesUrl(): string {
        return $this->topology['imagesUrl'];
    }

}
