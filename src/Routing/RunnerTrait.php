<?php

namespace Core\Routing;

use Psr\Container\ContainerInterface;
use \ReflectionParameter;
use \ReflectionMethod;
use \Exception;

trait RunnerTrait
{
    protected ContainerInterface $container;

    public function runAction(object $class, string $method): mixed
    {
        if (!method_exists($class, $method)) {
            throw new Exception("Cannot find method " . $method . ' in class ' . get_class($class));
        }

        $r = new ReflectionMethod($class, $method);
        $methodParameters = $r->getParameters();
        $params = [];

        foreach ($methodParameters as $param) {
            /** @var ReflectionParameter $param */
            if ($param->hasType()) {
                $typeHint = $param->getType()->getName();
                $dep = $this->container->get($typeHint);
                $params[] = $dep;
            }
        }

        return $r->invokeArgs($class, $params);
    }
}
