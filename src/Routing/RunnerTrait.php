<?php

declare(strict_types=1);

namespace Core\Routing;

use Psr\Container\ContainerInterface;
use \ReflectionNamedType;
use \ReflectionMethod;
use \ReflectionParameter;
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
        $methodParametres = $r->getParameters();

        $params = [];

        foreach ($methodParametres as $param) {
            /** @var ReflectionParameter $param */
            $type = $param->getType();
            if ($type instanceof ReflectionNamedType) {
                $typeHint = $type->getName();
                /** @var object $dep */
                $dep = $this->container->get($typeHint);
                $params[] = $dep;
            }
        }

        return call_user_func_array([$class, $method], $params);
    }

}
