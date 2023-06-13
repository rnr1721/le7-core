<?php

declare(strict_types=1);

namespace Core\Bundles;

use Core\Interfaces\BundleInterface;
use Core\Interfaces\ConfigInterface;

class Root implements BundleInterface
{

    protected ConfigInterface $config;

    public function __construct(
            ConfigInterface $config
    )
    {
        $this->config = $config;
    }

    public function init(): void
    {
        
    }

    public function getConflicts(): array
    {
        return [];
    }

    public function getDescription(): string
    {
        return _('System app bundle. Required!');
    }

    public function getName(): string
    {
        return 'root';
    }

    public function getRequired(): array
    {
        return [];
    }

    public function getConfig(): array
    {
        return [];
    }

    public function getMenu(): array
    {
        return [];
    }

    public function getPath(): string
    {
        return __DIR__;
    }

    public function getRoutes(): array
    {
        return $this->config->array('routes') ?? [];
    }
}
