<?php

namespace le7\Core\Instances;

use le7\Core\Config\TopologyFsInterface;
use Psr\Container\ContainerInterface;
use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionNamedType;
use \ReflectionParameter;

abstract class RouteRunner {

    protected TopologyFsInterface $topologyFs;
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container, TopologyFsInterface $tolologyFs) {
        $this->container = $container;
        $this->topologyFs = $tolologyFs;
    }

    public function getController(string $class, RouteInterface $route): object {
        // Prepare the controller with parametres
        $controllerMeat = new ReflectionClass($class);
        $controller = $controllerMeat->newInstanceWithoutConstructor();
        $controller->route = $route;
        
        foreach ($this->getInjectionProperties('base') as $propertyName => $propertyValue) {
            $controller->{$propertyName} = $this->container->get($propertyValue);
        }

        $routeDeps = $this->getInjectionProperties($route->getType());
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

    public function getInjectionProperties(string $routeType): array {
        $depFolder = $this->topologyFs->getConfigUserPath().DIRECTORY_SEPARATOR.'prop_injection';
        $file = $depFolder.DIRECTORY_SEPARATOR.$routeType.'.php';
        if (file_exists($file)) {
            $result = require($file);
            if (is_array($result)) {
                return $result;
            }
        }
        return [];
    }

}
