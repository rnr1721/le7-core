<?php

declare(strict_types=1);

namespace Core\Routing;

use Core\Interfaces\RouteHttp;

class RouteHttpGeneric extends RouteGeneric implements RouteHttp
{

    public function getBase(): string
    {
        return $this->getString('base');
    }

    public function getBaseRoot(): string
    {
        return $this->getString('base_root');
    }

    public function getMethod(): string
    {
        return $this->getString('method');
    }

    public function getUri(): string
    {
        return $this->getString('uri');
    }

    public function getResponse(): int
    {
        return $this->getInt('response');
    }

    public function getMiddleware(): array
    {
        return $this->getArray('middleware');
    }

    public function getParam(int $param, string|null $default = null): string|null
    {
        if (array_key_exists($param, $this->getArray('params'))) {
            return $this->route['params'][$param];
        }
        return $default;
    }

    public function getCsrf(): bool
    {
        return $this->getBool('csrf');
    }

}
