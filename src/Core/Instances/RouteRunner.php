<?php

namespace le7\Core\Instances;

use Psr\Container\ContainerInterface;
use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionNamedType;
use \ReflectionParameter;

abstract class RouteRunner {

    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getController(string $class, RouteInterface $route): object {
        // Prepare the controller with parametres
        $controllerMeat = new ReflectionClass($class);
        $controller = $controllerMeat->newInstanceWithoutConstructor();
        $controller->route = $route;

        foreach ($this->getBaseProperties() as $propertyName => $propertyValue) {
            $controller->{$propertyName} = $this->container->get($propertyValue);
        }

        $routeDeps = $this->getPublicProperties($route->getType());
        foreach ($routeDeps as $propertyName => $propertyValue) {
            $controller->{$propertyName} = $this->container->get($propertyValue);
        }

        if (method_exists($controller, '__construct')) {
            $this->runAction($controller, '__construct');
        }

        return $controller;
    }

    public function runAction(object $class, string $method) {
        $r = new ReflectionMethod($class, $method);
        $methodParametres = $r->getParameters();

        $params = [];

        foreach ($methodParametres as $param) {
            /** @var ReflectionParameter $param */
            $type = $param->getType();
            if ($type instanceof ReflectionNamedType) {
                $typeHint = $type->getName();
                $params[] = $this->container->get($typeHint);
            }
        }
        return call_user_func_array([$class, $method], $params);
    }

    public function getBaseProperties(): array {
        return array(
            'config' => 'le7\Core\Config\ConfigInterface',
            'uconfig' => 'le7\Core\Config\UserConfigInterface',
            'topologyFs' => 'le7\Core\Config\TopologyFsInterface',
            'log' => 'le7\Core\ErrorHandling\ErrorLogInterface',
            'ulog' => 'Psr\Log\LoggerInterface',
            'locales' => 'le7\Core\Locales\LocalesInterface',
            'translate' => 'le7\Core\Locales\TranslateInterface',
            'messages' => 'le7\Core\Messages\MessageCollectionInterface',
            'cache' => 'Psr\SimpleCache\CacheInterface',
            'ulib' => 'le7\Custom\UserGlobalLibrary',
            'helpers' => 'le7\Custom\UserHelpersLibrary',
            'dbFactory' => 'le7\Core\Database\DatabaseFactory'
        );
    }

    abstract public function getPublicProperties(string $routeType): array;
}
