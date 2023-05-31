<?php

declare(strict_types=1);

namespace Core\Bundles;

use Core\Interfaces\BundleInterface;
use Core\Interfaces\RouteRepositoryInterface;
use Core\Interfaces\ConfigInterface;

class Root implements BundleInterface
{

    protected ConfigInterface $config;
    protected RouteRepositoryInterface $routeRepository;

    public function __construct(
            ConfigInterface $config,
            RouteRepositoryInterface $routeRepository
    )
    {
        $this->config = $config;
        $this->routeRepository = $routeRepository;
    }

    public function init(): void
    {
        /** @var array<array-key, array> $routes */
        $routes = $this->config->array('routes') ?? [];
        $this->routeRepository->setRouteCollection($routes);
    }

    public function conflict(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return _('System app bundle. Required!');
    }

    public function getName(): string
    {
        return 'Root';
    }

    public function require(): array
    {
        return [];
    }

    public function config(): array
    {
        return [];
    }

}
