<?php

namespace le7\Core\Instances;

use Psr\Container\ContainerInterface;
use \ReflectionClass;
use \ReflectionNamedType;

class RouteRunner {

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getController(string $class, RouteInterface $route): object {
        // Prepare the controller with parametres
        $controllerMeat = new ReflectionClass($class);
        $classParameters = $controllerMeat->getConstructor()->getParameters();

        $params = [];

        foreach ($classParameters as $param) {
            $type = $param->getType();
            if ($type instanceof ReflectionNamedType) {
                $typeHint = $type->getName();
                $params[] = $this->container->get($typeHint);
            }
        }
        $controller = $controllerMeat->newInstanceArgs($params);
        $controller->route = $route;
        return $controller;
    }

}
