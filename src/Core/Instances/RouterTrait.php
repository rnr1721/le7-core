<?php

declare(strict_types=1);

namespace App\Core\Instances;

trait RouterTrait {
    
    private function getController(string $class, string $namespace, string $namespaceSystem): array|null {

        $result = array(
            'controller' => $class,
            'class' => ''
        );

        $usr = $namespace . '\\' . ucfirst($class) . 'Controller';
        $sys = $namespaceSystem . '\\' . ucfirst($class) . 'Controller';
        if (class_exists($usr)) {
            $result['class'] = $usr;
        } else {
            if (class_exists($sys)) {
                $result['class'] = $sys;
            } else {
                return null;
            }
        }
        return $result;
    }
    
}
