<?php

declare(strict_types=1);

namespace App\Core\Instances;

use App\Core\Config\ConfigInterface;
use App\Core\Request\Request;
use App\Core\Config\TopologyFsInterface;

class RouteCollection {

    private ConfigInterface $config;
    private Request $request;
    private array $routes = array();
    private string $root;

    //private TopologyFsInterface $topologyFs;

    public function __construct(ConfigInterface $config, TopologyFsInterface $topologyFs, Request $request) {
        $this->request = $request;
        $this->config = $config;
        $this->root = ($request->getBase() === '/' ? '' : $request->getBase());

        $locales = array_keys($this->config->getLocales());
        $defLang = $this->config->getDefaultLanguage();

        $routeConfig = $topologyFs->getConfigUserPath() . DIRECTORY_SEPARATOR . 'routes.php';
        if (file_exists($routeConfig)) {
            $routes = require($routeConfig);
            foreach ($routes as $route) {
                if (isset($route['multilang']) && $route['multilang'] === true) {
                    foreach ($locales as $lang) {
                        if ($lang !== $defLang) {
                            $this->addRoute(
                                    $route['key'] . '_' . $lang,
                                    $route['type'],
                                    $route['address'].'/'.$lang,
                                    $route['namespace'],
                                    $route['paramsCount'],
                                    $route['namespaceSystem'],
                                    $lang
                            );
                        }
                    }
                    $this->addRoute(
                            $route['key'] . '_' . $defLang,
                            $route['type'],
                            $route['address'],
                            $route['namespace'],
                            $route['paramsCount'],
                            $route['namespaceSystem'],
                            $defLang
                    );
                } else {
                    $this->addRoute(
                            $route['key'],
                            $route['type'],
                            $route['address'],
                            $route['namespace'],
                            $route['paramsCount'],
                            $route['namespaceSystem'],
                            $defLang
                    );
                }
            }
        }

        foreach ($locales as $lang) {
            if ($lang !== $defLang) {
                $this->addRoute('web_' . $lang, 'web', $lang, 'App\Controller\Web', 7, '', $lang);
            }
        }
        $this->addRoute('web_' . $defLang, 'web', '', 'App\Controller\Web', 7, '', $defLang);

    }

    private function generatePattern(int $paramsCount): string {
        $pattern = '';
        if ($paramsCount === 0) {
            return $pattern;
        }
        for ($index = 1; $index <= $paramsCount; $index++) {
            $pattern .= '?(/[A-Za-z0-9_-]+)';
        }
        return $pattern . '?';
    }

    private function addRoute(string $key, string $type, string $address, string $controllerNamespace, int $paramsCount = 7, $systemNamespace = '', string|null $language = null): self {
        if (empty($systemNamespace)) {
            if ($type === 'api') {
                $systemNamespace = 'App\Core\Controllers\System\Api';
            } else {
                $systemNamespace = 'App\Core\Controllers\System\Web';
            }
        }
        $this->routes[$key] = array(
            //'pattern' => preg_replace('/\/{(.*?)}/', '/(.*?)', $this->root . $pattern . ($universalPattern ? $this->urlOptions : '')),
            'pattern' => $this->root . '/' . $address . $this->generatePattern($paramsCount),
            'base' => $address,
            'namespace' => $controllerNamespace,
            'namespaceSys' => $systemNamespace,
            'type' => $type,
            'language' => $language
        );
        return $this;
    }

    public function getRoutes(): array {
        return $this->routes;
    }

}
