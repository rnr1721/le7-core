<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Utils\Strings;
use \ReflectionMethod;
use \ReflectionClass;
use \ReflectionAttribute;
use \ReflectionProperty;
use \ReflectionNamedType;

class DispatcherReflection
{

    public Strings $string;

    public function __construct(Strings $string)
    {
        $this->string = $string;
    }

    /**
     * Get class parameters (attributes)
     * @param class-string $class
     * @return array
     */
    public function getClassParams(string $class): array
    {
        $rClass = new ReflectionClass($class);
        $attributes = $rClass->getAttributes();
        return $this->getAttributes($attributes);
    }

    /**
     * Get method parameters (attributes)
     * @param class-string $class
     * @param string $method
     * @return array
     */
    public function getMethodParams(string $class, string $method): array
    {
        if (!method_exists($class, $method)) {
            return $this->getAttributes([]);
        }
        $rMethod = new ReflectionMethod($class, $method);
        $attributes = $rMethod->getAttributes();
        return $this->getAttributes($attributes);
    }

    /**
     * Get allowed params and middleware
     * @param array $attributes
     * @return array
     */
    public function getAttributes(array $attributes): array
    {
        $result = [
            'csrf' => true,
            'active' => true,
            'allowedParams' => 0,
            'middleware' => []
        ];

        if (empty($attributes)) {
            return $result;
        }

        foreach ($attributes as $attribute) {

            $param = basename(str_replace('\\', '/', $attribute->getName()));

            if ($param === 'Params') {
                $arguments = $attribute->getArguments();
                if (array_key_exists('allow', $arguments)) {
                    $result['allowedParams'] = (int) $arguments['allow'];
                }
                if (array_key_exists('csrf', $arguments)) {
                    $result['csrf'] = (bool) $arguments['csrf'];
                }
                if (array_key_exists('active', $arguments)) {
                    $result['active'] = (bool) $arguments['active'];
                }
            }

            if ($param === 'Middleware') {
                $result['middleware'] = $this->getAttributeParamClasses($attribute);
            }
        }
        return $result;
    }

    public function getAttributeParamClasses(ReflectionAttribute $attribute): array
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

    /**
     * Get typed class properties
     * @param object|class-string $class
     * @param array|string $types
     * @return array
     */
    public function getClassProperties(object|string $class, array|string $types = []): array
    {
        if (is_string($types)) {
            $types = explode(',', $types);
        }
        $reflect = new ReflectionClass($class);
        $props = $reflect->getProperties();
        $ownProps = [];
        /** @var ReflectionProperty $prop */
        foreach ($props as $prop) {
            if ($prop->getType() !== null) {
                /** @var ReflectionNamedType $reflectionType */
                $reflectionType = $prop->getType();
                $type = $reflectionType->getName();
                if (in_array($type, $types) || $types === []) {
                    $name = $prop->getName();
                    $comment = $prop->getDocComment();
                    if (is_string($comment)) {
                        $comment = $this->string->parseDocComment($comment);
                        $commentF = $this->string->removeNewLine($comment);
                    }
                    $default = $prop->getDefaultValue();
                    $property = [
                        'name' => $name,
                        'type' => $type,
                        'annotation' => $commentF ?? '',
                        'default' => $default
                    ];
                    $commentF = '';
                    $ownProps[] = $property;
                }
            }
        }
        return $ownProps;
    }

}
