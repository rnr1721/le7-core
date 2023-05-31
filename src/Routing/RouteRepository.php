<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Interfaces\RouteRepositoryInterface;
use Core\Interfaces\RequestInterface;
use Core\Interfaces\ConfigInterface;
use \Exception;

class RouteRepository implements RouteRepositoryInterface
{

    /**
     * Default locales if not in config
     * 
     * @var array
     */
    protected array $defaultLocales = [
        'en' => 'en_US|English'
    ];

    /**
     * System request object
     * 
     * @var RequestInterface
     */
    protected RequestInterface $request;

    /**
     * Config manager
     * 
     * @var ConfigInterface
     */
    protected ConfigInterface $config;
    protected array $routes = array();

    /**
     * Public base with folder
     * 
     * @var string
     */
    protected string $root;

    /**
     * Raw routes
     * 
     * @var array<array-key, array>
     */
    protected array $rawRoutes = [];

    public function __construct(
            ConfigInterface $config,
            RequestInterface $request
    )
    {
        $this->config = $config;
        $this->request = $request;
        $this->root = $request->getBase() === '/' ? '' : $request->getBase();
    }

    private function validateRoute(array $route): void
    {
        $requiredKeys = ['key', 'type', 'address', 'namespace', 'params', 'multilang'];
        foreach ($requiredKeys as $key) {
            if (!isset($route[$key])) {
                throw new Exception("All routes must have key '$key'");
            }
        }
        if (!in_array($route['type'], ['api', 'web'])) {
            throw new Exception("All routes must have type - 'api' or 'web'");
        }
        if (!is_int($route['params'])) {
            throw new Exception("All routes must have key 'params' - integer");
        }
        if (!is_bool($route['multilang'])) {
            throw new Exception("All routes must have key 'multilang' - boolean");
        }
    }

    /**
     * 
     * @param array<array-key, string|int|bool> $route
     * @param string $address
     * @return void
     */
    private function addMultilangRoutes(array $route, string $address): void
    {
        $locales = array_keys($this->config->array('locales') ?? $this->defaultLocales);
        $defLang = $this->config->string('defaultLanguage') ?? 'en';

        /** @var string $key */
        $key = $route['key'];
        /** @var string $type */
        $type = $route['type'];
        /** @var string $namespace */
        $namespace = $route['namespace'];
        /** @var int $params */
        $params = $route['params'];

        foreach ($locales as $lang) {
            /** @var string $lang */
            if ($lang !== $defLang) {
                $this->addRoute(
                        $key . '_' . $lang,
                        $type,
                        $lang . rtrim($address, '/'),
                        $namespace,
                        $params,
                        $lang
                );
            }
        }
        $this->addRoute(
                $key . '_' . $defLang,
                $type,
                $address,
                $namespace,
                $params,
                $defLang
        );
    }

    private function generatePattern(int $paramsCount): string
    {
        $pattern = '/?([A-Za-z0-9_-]+)';
        if ($paramsCount === 0) {
            return $pattern;
        }
        for ($index = 2; $index <= $paramsCount; $index++) {
            $pattern .= '?(/[A-Za-z0-9_-]+)';
        }
        return $pattern . '?';
    }

    private function addRoute(
            string $key,
            string $type,
            string $address,
            string $controllerNamespace,
            int $paramsCount = 7,
            string|null $language = null
    ): self
    {
        $pattern = '#^' . ltrim($address, '/') . $this->generatePattern($paramsCount) . '$#';

        $this->routes[$key] = array(
            'pattern' => $pattern,
            'base' => $address,
            'namespace' => $controllerNamespace,
            'type' => $type,
            'language' => $language
        );
        return $this;
    }

    public function setRouteCollection(array $routes): self
    {
        $this->rawRoutes = array_merge($this->rawRoutes, $routes);
        return $this;
    }

    public function getRoutes(): array
    {
        if (count($this->routes) !== 0) {
            return $this->routes;
        }
        foreach ($this->rawRoutes as $route) {
            /** @var array<array-key, string|int|bool> $route */
            $this->validateRoute($route);
            /** @var string $addressRaw */
            $addressRaw = $route['address'];
            $address = '/' . ltrim($addressRaw, '/');
            if ($route['multilang'] ?? false) {
                $this->addMultilangRoutes($route, $address);
            } else {
                /** @var string $key */
                $key = $route['key'];
                /** @var string $type */
                $type = $route['type'];
                /** @var string $namespace */
                $namespace = $route['namespace'];
                /** @var int $params */
                $params = $route['params'];
                $this->addRoute(
                        $key,
                        $type,
                        $address,
                        $namespace,
                        $params,
                        $this->config->string('defaultLanguage', 'en')
                );
            }
        }
        return $this->routes;
    }

}
