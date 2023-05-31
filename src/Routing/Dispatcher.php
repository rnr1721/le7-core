<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Routing\DispatcherReflection;
use Core\Interfaces\RequestInterface;
use Core\Interfaces\ConfigInterface;
use Psr\SimpleCache\CacheInterface;
use \Exception;

abstract class Dispatcher
{

    protected bool $isProduction = false;
    protected string $defaultLanguage = '';
    protected string $defaultController = '';
    protected string $defaultAction = '';
    protected string $notfoundController = '';
    protected string $notfoundWebNamespace = '';
    protected string $notfoundApiNamespace = '';
    protected array $locales = [];
    protected DispatcherReflection $reflection;
    protected CacheInterface $cache;
    protected RequestInterface $request;
    protected ConfigInterface $config;
    protected string $root;

    public function __construct(
            CacheInterface $cache,
            ConfigInterface $config,
            RequestInterface $request,
            DispatcherReflection $reflection
    )
    {
        $this->config = $config;
        $this->root = $request->getBase();
        $this->request = $request;
        $this->cache = $cache;
        $this->reflection = $reflection;
        $this->defaultController = $config->string('defaultController') ?? 'index';
        $this->defaultAction = $config->string('defaultAction') ?? 'index';
        $this->notfoundController = $config->string('notfoundController') ?? 'notfound';
        $this->notfoundWebNamespace = $config->string('notfoundWebNamespace', '') ?? '';
        $this->notfoundApiNamespace = $config->string('notfoundApiNamespace', '') ?? '';
        $this->defaultLanguage = $config->string('defaultLanguage') ?? 'en';
        $this->locales = $config->array('locales') ?? ['en' => 'en_US|English'];
        $this->isProduction = $config->bool('isProduction');
    }

    /**
     * This method process raw route data and give full current route data.
     * It check if controller class and action method exists,
     * check language, controller middleware, URL params etc.
     * @param string $uri Current URI
     * @param string $method Method - GET, POST, PUT etc
     * @param array $data Route raw data from RouteBuilder
     * @param string $actionPrefix Prefix of action names
     * @param string $actionSuffix Suffix of action names
     * @param bool $notFound Predefined notfound
     * @return array|null
     * @throws Exception
     */
    protected function processRoute(
            string $uri,
            string $method,
            array $data,
            string $actionPrefix = '',
            string $actionSuffix = 'Action',
            bool $notFound = true
    ): array|null
    {

        // Try get from cache
        $name = 'route_' . md5((string) $this->request->getUri());
        if ($this->isProduction) {
            if ($this->cache->has($name)) {
                return $this->cache->get($name);
            }
        }

        // Set language
        $language = $data['language'] ?? $this->defaultLanguage;

        // If notFound anycase
        if ($data['case'] === '404' or $notFound) {
            return $this->getNotFound(
                            $uri,
                            $method,
                            $data,
                            $actionSuffix
            );
        }

        $normalized = $this->normalizeControllerAction($data['params']);
        if (!$normalized) {
            return $this->getNotFound(
                            $uri,
                            $method,
                            $data,
                            $actionSuffix
            );
        }

        $controller = $normalized['controller'];
        $action = $normalized['action'];

        $markToDelete = array();

        // Check if controller class exists
        $controllerClass = $this->getControllerClass($data['namespace'], $controller);

        if ($controllerClass) {
            $markToDelete[] = 0;
        } else {
            $controllerClass = $this->getControllerClass($data['namespace']);
            if ($controllerClass === null) {
                throw new Exception("Please set up routes! Can not find controller " . $controller . ' with namespace ' . $data['namespace']);
            }
        }

        // Get method name for action
        $actionMethod = $this->getActionMethod($actionPrefix, $actionSuffix, $action);
        if (method_exists($controllerClass, $actionMethod)) {
            $markToDelete[] = 1;
        } else {
            $actionMethod = $this->getActionMethod($actionPrefix, $actionSuffix);
            // If default method not found
            if (!method_exists($controllerClass, $actionMethod)) {
                return $this->getNotFound(
                                $uri,
                                $method,
                                $data,
                                $actionSuffix
                );
            }
        }

        // Unset params that correct
        foreach ($markToDelete as $item) {
            unset($data['params'][$item]);
        }

        $controllerParams = $this->reflection->getClassParams($controllerClass);
        $actionParams = $this->reflection->getMethodParams($controllerClass, $actionMethod);

        $allowedParams = $controllerParams['allowedParams'];
        if (!empty($actionParams['allowedParams'])) {
            $allowedParams = $actionParams['allowedParams'];
        }

        /** @var string[] $middleware */
        $middleware = array_merge($controllerParams['middleware'], $actionParams['middleware']);

        if (count($data['params']) > $allowedParams || $actionParams['active'] === false) {
            return $this->getNotFound(
                            $uri,
                            $method,
                            $data,
                            $actionSuffix,
                            $language);
        }

        $result = array(
            'type' => $data['type'],
            'case' => $data['case'],
            'base' => $data['base'],
            'base_root' => $data['base_root'],
            'method' => $method,
            'uri' => '/' . ltrim($uri, '/'),
            'language' => $language,
            'controller' => $controller,
            'action' => $action,
            'controllerClass' => $controllerClass,
            'actionMethod' => $actionMethod,
            'response' => 200,
            'params' => $data['params'],
            'middleware' => array_unique($middleware),
            'active' => $actionParams['active'],
            'csrf' => $actionParams['csrf'],
            'notfound' => $this->getNotFound(
                    $uri,
                    $method,
                    $data,
                    $actionSuffix,
                    $language
            )
        );

        if ($this->isProduction) {
            $this->cache->set($name, $result);
        }

        return $result;
    }

    /**
     * Get notfound route data
     * @param string $uri Current URI
     * @param string $method Method - GET, POST, PUT etc
     * @param array $data Raw route data
     * @param string $actionPrefix action Prefix
     * @param string $actionSuffix action Suffix
     * @param string $language Current language
     * @return array
     * @throws Exception
     */
    protected function getNotFound(
            string $uri,
            string $method,
            array $data,
            string $actionSuffix = '',
            string $language = ''
    ): array
    {
        if (!array_key_exists($language, $this->locales)) {
            $language = $this->defaultLanguage;
        }

        $notfoundNamespace = '';
        if ($data['type'] === 'api') {
            $notfoundNamespace = $this->notfoundApiNamespace;
        } elseif ($data['type'] === 'web') {
            $notfoundNamespace = $this->notfoundWebNamespace;
        }

        $namespace = ($notfoundNamespace === '' ? $data['namespace'] : $notfoundNamespace);

        $controllerClass = $this->getControllerClass($namespace, $this->notfoundController);
        if (!$controllerClass) {
            throw new Exception("Please add notfound controller in " . $data['namespace'] . " to see notfound page");
        }

        $result = array(
            'type' => $data['type'],
            'case' => $data['case'],
            'base' => $data['base'],
            'base_root' => $data['base_root'],
            'method' => $method,
            'uri' => '/' . ltrim($uri, '/'),
            'language' => $language,
            'controller' => $this->notfoundController,
            'action' => $this->defaultAction,
            'controllerClass' => $controllerClass,
            'actionMethod' => $this->defaultAction . '' . $actionSuffix,
            'response' => 404,
            'params' => $data['params'],
            'middleware' => [],
            'active' => true,
            'csrf' => true
        );
        return $result;
    }

    private function normalizeControllerAction(array $params): array|null
    {
        $controller = $this->defaultController;
        if (isset($params[0])) {
            $controller = $params[0];
            if ($controller === $this->defaultController) {
                return null;
            }
        }

        $action = $this->defaultAction;
        if (isset($params[1])) {
            $action = $params[1];
            if ($action === $this->defaultAction) {
                return null;
            }
        }
        return [
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Get controller class or null
     * @param string $namespace
     * @param string|null $controller
     * @return class-string|null
     */
    protected function getControllerClass(
            string $namespace,
            ?string $controller = null
    ): string|null
    {
        if ($controller === null) {
            $controller = $this->defaultController;
        }
        $class = '\\' . $namespace . '\\' . ucfirst($controller) . 'Controller';
        if (class_exists($class)) {
            return $class;
        }
        return null;
    }

    private function getActionMethod(
            string $prefix,
            string $suffix,
            ?string $action = null
    ): string
    {
        if ($action) {
            return $action . $prefix . $suffix;
        }
        return $this->defaultAction . $prefix . $suffix;
    }

    abstract public function getRoute(
            string $uri,
            array $parsedRoute,
            bool $notFound = true
    ): array;
}
