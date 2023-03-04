<?php

declare(strict_types=1);

namespace App\Core\Instances;

class RouteHttp extends Route implements RouteHttpInterface {

    public function getBase():string{
        return $this->route['base'];
    }

    public function getBaseRoot(): string {
        return $this->route['base_root'];
    }

    public function getMethod(): string {
        return $this->route['method'];
    }

    public function getUri(): string {
        return $this->route['uri'];
    }

    public function getResponse(): int {
        return $this->route['response'];
    }

    public function getMiddleware(): array
    {
        return $this->route['middleware'];
    }

    public function getInject(): array
    {
        return $this->route['inject'];
    }

}
