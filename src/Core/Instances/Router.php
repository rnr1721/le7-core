<?php

declare(strict_types=1);

namespace App\Core\Instances;

use App\Core\Request\Request;
use App\Core\Config\ConfigInterface;
use \ReflectionMethod;
use \ReflectionClass;
use \ReflectionAttribute;
use Psr\SimpleCache\CacheInterface;

abstract class Router
{

    use RouterTrait;

    protected CacheInterface $cache;
    protected Request $request;
    protected ConfigInterface $config;
    protected string $root;
    protected bool $notFound = false;

    public function __construct(CacheInterface $cache, ConfigInterface $config, Request $request, string $root, bool $notFound = false)
    {
        $this->config = $config;
        $this->root = $root;
        $this->request = $request;
        $this->notFound = $notFound;
        $this->cache = $cache;
    }

    protected function processRoute(string $uri, string $method, array $data, string $actionPrefix = '', string $actionSuffix = 'Action'): array|null
    {

        $name = 'route_' . md5((string) $this->request->getUri());
        if ($this->config->getIsProduction()) {
            if ($this->cache->has($name)) {
                return $this->cache->get($name);
            }
        }

        $namespace = $data['namespace'];
        $namespaceSystem = $data['namespaceSys'];
        $params = $data['params'];
        $defController = $this->config->getDefaultController();
        $defAction = $this->config->getDefaultAction();

        if ($data['language']) {
            $language = $data['language'];
        } else {
            $language = $this->config->getDefaultLanguage();
        }

        if ($data['case'] === '404' or $this->notFound) {
            return $this->getNotFound($uri, $method, $data, $namespace, $namespaceSystem, $actionPrefix, $actionSuffix);
        }

        if (isset($params[0])) {
            $controller = $params[0];
            if ($controller === $defController) {
                $set404 = true;
            }
        } else {
            $controller = $defController;
        }

        if (isset($params[1])) {
            $action = $params[1];
            if ($action === $defAction) {
                $set404 = true;
            }
        } else {
            $action = $defAction;
        }

        if (isset($set404)) {
            return $this->getNotFound($uri, $method, $data, $namespace, $namespaceSystem, $actionPrefix, $actionSuffix);
        }

        $markToDelete = array();

        $pController = $this->getController($controller, $namespace, $namespaceSystem);
        if (empty($pController)) {
            $pController = $this->getController($defController, $namespace, $namespaceSystem);
        } else {
            $markToDelete[] = 0;
        }

        $controller = $pController['controller'];
        $controllerAbsolute = $pController['class'];

        $pAction = $action . $actionPrefix . $actionSuffix;

        if (method_exists($controllerAbsolute, $pAction)) {
            $actionAbsolute = $pAction;
            $markToDelete[] = 1;
        } else {
            $actionAbsolute = $defAction . $actionPrefix . $actionSuffix;
        }

        // Unset params that correct
        foreach ($markToDelete as $item) {
            unset($data['params'][$item]);
        }

        $controllerParams = $this->getClassParams($controllerAbsolute);
        $actionParams = $this->getMethodParams($controllerAbsolute, $actionAbsolute);

        $allowedParams = $controllerParams['allowedParams'];
        if (!empty($actionParams['allowedParams'])) {
            $allowedParams = $actionParams['allowedParams'];
        }

        $middleware = array_merge($controllerParams['middleware'], $actionParams['middleware']) ?? [];
        $inject = array_merge($controllerParams['inject'], $actionParams['inject']) ?? [];

        if (count($data['params']) > $allowedParams) {
            return $this->getNotFound($uri, $method, $data, $namespace, $namespaceSystem, $actionPrefix, $actionSuffix, $language);
        }

        $result = array(
            'type' => $data['type'],
            'case' => $data['case'],
            'base' => $data['base'],
            'base_root' => $data['base_root'],
            'method' => $method,
            'uri' => $uri,
            'language' => $language,
            'controller' => $controller,
            'action' => $action,
            'controllerClass' => $controllerAbsolute,
            'actionMethod' => $actionAbsolute,
            'response' => 200,
            'params' => $data['params'],
            'middleware' => array_unique($middleware),
            'inject' => array_unique($inject),
            'notfound' => $this->getNotFound($uri, $method, $data, $namespace, $namespaceSystem, $actionPrefix, $actionSuffix, $language)
        );

        if ($this->config->getIsProduction()) {
            $this->cache->set($name, $result);
        }

        return $result;
    }

    protected function getNotFound(
            string $uri,
            string $method,
            array $data,
            string $namespace,
            string $namespaceSystem,
            string $actionPrefix = '',
            string $actionSuffix = '',
            string $language = ''
    ): array
    {
        if (!array_key_exists($language, $this->config->getLocales())) {
            $language = $this->config->getDefaultLanguage();
        }
        $nfController = $this->config->getNotfoundController();
        $nfAction = $this->config->getDefaultAction();
        $controller = $this->getController($nfController, $namespace, $namespaceSystem);
        $result = array(
            'type' => $data['type'],
            'case' => $data['case'],
            'base' => $data['base'],
            'base_root' => $data['base_root'],
            'method' => $method,
            'uri' => $uri,
            'language' => $language,
            'controller' => $controller['controller'],
            'action' => $nfAction,
            'controllerClass' => $controller['class'],
            'actionMethod' => $nfAction . $actionPrefix . $actionSuffix,
            'response' => 404,
            'params' => $data['params'],
            'middleware' => [],
            'inject' => []
        );
        return $result;
    }

    private function getAttributes(array $attributes): array
    {
        $result = [
            'allowedParams' => 0,
            'middleware' => [],
            'inject' => []
        ];

        if (empty($attributes)) {
            return $result;
        }

        foreach ($attributes as $attribute) {

            $param = basename(str_replace('\\', '/', $attribute->getName()));

            if ($param === 'Params') {
                $arguments = $attribute->getArguments();
                if (array_key_exists('allow', $arguments)) {
                    $result['allowedParams'] = intval($arguments['allow']);
                }
            }

            if ($param === 'Middleware') {
                $result['middleware'] = $this->getAttributeParamClasses($attribute);
            }

            if ($param === 'Inject') {
                $result['inject'] = $this->getAttributeParamClassesKV($attribute);
            }
        }
        return $result;
    }

    private function getAttributeParamClasses(ReflectionAttribute $attribute): array
    {
        $result = [];
        $arguments = $attribute->getArguments();
        if (isset($arguments[0]) && is_array($arguments[0])) {
            foreach ($arguments[0] as $item) {
                if (is_string($item)) {
                    $result[] = $item;
                }
            }
        }
        return $result;
    }

    private function getAttributeParamClassesKV(ReflectionAttribute $attribute): array
    {
        $result = [];
        $arguments = $attribute->getArguments();
        foreach ($arguments as $key => $value) {
            if (is_string($value)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    private function getClassParams(string $class): array
    {
        $rClass = new ReflectionClass($class);
        $attributes = $rClass->getAttributes();
        return $this->getAttributes($attributes);
    }

    private function getMethodParams(string $class, string $method)
    {
        $rMethod = new ReflectionMethod($class, $method);
        $attributes = $rMethod->getAttributes();
        return $this->getAttributes($attributes);
    }

    abstract public function getRoute(string $uri, array $parsedRoute): array;
}
