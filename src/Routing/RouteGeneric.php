<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Interfaces\RouteInterface;
use \Exception;

class RouteGeneric implements RouteInterface
{

    protected array $route = array();

    /**
     * @param array<array-key, int|string|class-string|array> $route
     */
    public function __construct(array $route)
    {
        $this->route = $route;
    }

    public function getType(): string
    {
        return $this->getString('type');
    }

    public function getCase(): string
    {
        return $this->getString('case');
    }

    public function getController(): string
    {
        return $this->getString('controller');
    }

    public function getAction(): string
    {
        return $this->getString('action');
    }

    public function getControllerClass(): string
    {
        /** @var class-string $class */
        $class = $this->getString('controllerClass');
        return $class;
    }

    public function getActionMethod(): string
    {
        return $this->getString('actionMethod');
    }

    /**
     * @return array<array-key, string>
     */
    public function getParams(): array
    {
        return $this->getArray('params');
    }

    public function getLanguage(): string
    {
        return $this->getString('language');
    }

    public function exportArray(): array
    {
        return $this->route;
    }

    public function exportObject(): object
    {
        return (object) $this->route;
    }

    /**
     * @param string $name
     * @return string
     * @throws Exception
     */
    protected function getString(string $name): string
    {
        if (!isset($this->route[$name])) {
            throw new Exception($name . 'in route not exists');
        }
        if (!is_string($this->route[$name])) {
            throw new Exception($name . ' in route must be a string');
        }
        return $this->route[$name];
    }

    /**
     * @param string $name
     * @return array<array-key, string>
     * @throws Exception
     */
    protected function getArray(string $name): array
    {
        if (!isset($this->route[$name])) {
            throw new Exception($name . 'in route not exists');
        }
        if (is_array($this->route[$name])) {
            /** @var array<array-key, string> $result */
            $result = $this->route[$name];
            return $result;
        }
        throw new Exception($name . ' in route must be a array');
    }

    protected function getInt(string $name): int
    {
        if (!isset($this->route[$name])) {
            throw new Exception($name . 'in route not exists');
        }
        if (!is_int($this->route[$name])) {
            throw new Exception($name . ' in route must be a int');
        }
        return $this->route[$name];
    }

    protected function getBool(string $name): bool
    {
        if (!isset($this->route[$name])) {
            throw new Exception($name . 'in route not exists');
        }
        if (!is_bool($this->route[$name])) {
            throw new Exception($name . ' in route must be a bool');
        }
        return $this->route[$name];
    }

}
