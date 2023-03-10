<?php

declare(strict_types=1);

namespace App\Core\Instances;

use App\Core\Config\ConfigInterface;
use App\Core\Config\TopologyFsInterface;
use App\Core\Request\Request;
use Psr\SimpleCache\CacheInterface;

/**
 * Данный класс возвращает массив, с обработанными данными роутера,
 * то есть параметры, заявленный язык, и текущий Uri
 */
class InstanceHttpData
{

    private CacheInterface $cache;
    private RouteCollection $routeCollection;
    private ConfigInterface $config;
    private TopologyFsInterface $topology;
    private Request $request;

    public function __construct(CacheInterface $cache, ConfigInterface $config, Request $request, TopologyFsInterface $topologyFs, RouteCollection $routeCollection)
    {
        $this->config = $config;
        $this->topology = $topologyFs;
        $this->request = $request;
        $this->routeCollection = $routeCollection;
        $this->cache = $cache;
    }

    public function getCurrentRoute(): RouteHttpInterface
    {

        $root = $this->request->getBase();
        $uri = str_replace('//', '/', $this->request->getUri()->getPath());

        $parsedRoute = $this->parseUri($uri);

        $cleanUri = (string) $this->request->getUri()->getPath();

        $notfound = (str_contains($cleanUri, '//') ? true : false);

        $router = match ($parsedRoute['type']) {
            'api' => new RouterApi($this->cache, $this->config, $this->request, $root, $notfound),
            'web' => new RouterWeb($this->cache, $this->config, $this->request, $root, $notfound)
        };

        return new RouteHttp($router->getRoute($uri, $parsedRoute));
    }

    public function parseUri(string $uri): array
    {
        $result = array();
        $matchesFull = array();
        $root = $this->request->getBase();
        foreach ($this->routeCollection->getRoutes() as $rKey => $route) {
            preg_match('#^' . $route['pattern'] . '$#', $uri, $matchesFull, PREG_OFFSET_CAPTURE);
            if (!empty($matchesFull) && is_array($matchesFull)) {
                $result['type'] = $route['type'];
                $result['case'] = $rKey;
                $result['namespace'] = $route['namespace'];
                $result['namespaceSys'] = $route['namespaceSys'];
                $result['base'] = rtrim($root, '/') . '/' . ltrim($route['base'], '/');
                $result['base_root'] = '/' . ltrim($route['base'], '/');
                $result['language'] = $route['language'];
                break;
            } else {
                $result['type'] = 'web';
                $result['case'] = '404';
                $result['namespace'] = 'App\Controller\Web';
                $result['namespaceSys'] = 'App\Core\Controllers\System\Web';
                $result['base'] = $root;
                $result['base_root'] = '/';
                $result['language'] = $this->config->getDefaultLanguage();
            }
        }
        $matches = array_slice($matchesFull, 1);
        $result['params'] = array();
        foreach ($matches as $match) {
            $result['params'][] = trim($match[0], '/');
        }
        return $result;
    }

}
