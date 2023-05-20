<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Interfaces\RequestInterface;
use Core\Interfaces\RouteHttpInterface;
use Core\Interfaces\ConfigInterface;
use Core\Routing\DispatcherReflection;
use Psr\SimpleCache\CacheInterface;
use \Exception;

/**
 * Данный класс возвращает массив, с обработанными данными роутера,
 * то есть параметры, заявленный язык, и текущий Uri
 */
class RouteBuilderHttp
{

    private CacheInterface $cache;
    private RouteRepository $routeCollection;
    private ConfigInterface $config;
    private RequestInterface $request;
    private DispatcherReflection $reflection;
    private string $systemNamespace = 'Core\Controller\Web';

    public function __construct(
            CacheInterface $cache,
            ConfigInterface $config,
            RequestInterface $request,
            RouteRepository $routeCollection,
            DispatcherReflection $reflection,
    )
    {
        $this->config = $config;
        $this->request = $request;
        $this->routeCollection = $routeCollection;
        $this->cache = $cache;
        $this->reflection = $reflection;
    }

    /**
     * Get current HTTP route as object
     * @return RouteHttpInterface
     * @throws Exception
     */
    public function getCurrentRoute(): RouteHttpInterface
    {

        $uriRaw = str_replace('//', '/', $this->request->getUri()->getPath());

        $uri = rtrim($uriRaw, '/');

        // Remove from URI subfolder if exists
        $prefix = $this->request->getBase();
        if (substr($uri, 0, strlen($prefix)) == $prefix) {
            $uri = substr($uri, strlen($prefix));
        }

        $parsedRoute = $this->parseUri($uri);

        // Check uri for double-slash for declare notfound if contains
        $cleanUri = ltrim((string) $this->request->getUri()->withScheme(''), '//');
        $notFound = (str_contains($cleanUri, '//') ? true : false);

        /** @var Dispatcher $router */
        $router = match ($parsedRoute['type'] ?? 'web') {
            'api' => new DispatcherApi($this->cache, $this->config, $this->request, $this->reflection),
            'web' => new DispatcherWeb($this->cache, $this->config, $this->request, $this->reflection),
            default => throw new Exception('Cannot get router')
        };

        $routeData = $router->getRoute($uri, $parsedRoute, $notFound);

        return new RouteHttpGeneric($routeData);
    }

    /**
     * Parse URI by pattern and find true route
     * @param string $uri Current URI
     * @return array
     */
    public function parseUri(string $uri): array
    {
        if (rtrim($this->request->getBase(), '/') === $uri) {
            $uri = '/';
        }
        $result = array();
        $matchesFull = array();
        $root = $this->request->getBase();

        foreach ($this->routeCollection->getRoutes() as $rKey => $route) {
            preg_match($route['pattern'], $uri, $matchesFull, PREG_OFFSET_CAPTURE);
            if (!empty($matchesFull)) {
                $result['type'] = $route['type'];
                $result['case'] = $rKey;
                $result['namespace'] = $route['namespace'];
                $result['base'] = rtrim($root, '/') . '/' . trim($route['base'], '/');
                $result['base_root'] = '/' . ltrim($route['base'], '/');
                $result['language'] = $route['language'];
                break;
            }
        }

        if ($result === []) {
            $result = [
                'type' => 'web',
                'case' => 'web',
                'namespace' => $this->systemNamespace,
                'base' => $root,
                'base_root' => '/',
                'language' => $this->config->string('defaultLanguage', 'en')
            ];
        }
        $matches = array_slice($matchesFull, 1);
        $result['params'] = array();
        foreach ($matches as $match) {
            $result['params'][] = trim($match[0], '/');
        }

        return $result;
    }

}
