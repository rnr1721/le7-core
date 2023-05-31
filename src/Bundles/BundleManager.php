<?php

declare(strict_types=1);

namespace Core\Bundles;

use Core\Interfaces\ConfigInterface;
use Core\Interfaces\BundleInterface;
use Core\Interfaces\BundleManagerInterface;
use Core\Exceptions\BundleConflictException;
use Core\Exceptions\MissingRequiredBundleException;
use Psr\Container\ContainerInterface;
use \Exception;

class BundleManager implements BundleManagerInterface
{

    private ContainerInterface $container;
    private ConfigInterface $config;
    private array $bundles = [];
    private array $required = [];
    private bool $ready = false;

    public function __construct(
            ContainerInterface $container,
            ConfigInterface $config
    )
    {
        $this->container = $container;
        $this->config = $config;
        $this->addBundle(Root::class);
    }

    public function initAll(): void
    {
        if ($this->ready) {
            throw new Exception('Bundles already initialized');
        }
        $this->checkRequired();
        /** @var BundleInterface $bundleObject */
        foreach ($this->bundles as $bundleName => $bundleObject) {
            $this->checkConflicts($bundleName);
            $bundleObject->init();
        }
        $this->ready = true;
    }

    public function addBundle(string $bundle): self
    {
        if ($this->ready) {
            throw new Exception('You cant add bundles after init process');
        }
        if (!class_exists($bundle)) {
            throw new Exception('Bundle ' . $bundle . ' not exists');
        }
        if (!in_array(BundleInterface::class, class_implements($bundle))) {
            throw new Exception("Class " . $bundle . ' must be instance of ' . BundleInterface::class);
        }
        /** @var BundleInterface $bundleObject */
        $bundleObject = $this->container->get($bundle);
        $name = $bundleObject->getName();
        $this->required = array_unique(array_merge($this->required, $bundleObject->require()));
        $this->bundles[$name] = $bundleObject;
        $configName = 'bundles.config.' . $name;
        $configValue = $this->config->array($configName, []) ?? [];
        if (count($configValue) === 0) {
            $this->config->registerParam($configName, $bundleObject->config());
        }
        return $this;
    }

    public function addBundles(array $bundles): self
    {
        foreach ($bundles as $bundle) {
            $this->addBundle($bundle);
        }
        return $this;
    }

    protected function checkConflicts(string $currentBundleName): void
    {
        /** @var BundleInterface $bundleObject */
        foreach ($this->bundles as $bundleName => $bundleObject) {
            if ($bundleName !== $currentBundleName) {
                if (in_array($bundleName, $bundleObject->conflict())) {
                    throw new BundleConflictException("Bundle $currentBundleName conflicts with $bundleName");
                }
            }
        }
    }

    protected function checkRequired(): void
    {
        foreach ($this->required as $required) {
            if (!array_key_exists($required, $this->bundles)) {
                throw new MissingRequiredBundleException("Module required: " . $required);
            }
        }
    }

    public function getList(): array
    {
        $result = [];
        /** @var BundleInterface $bundleObject */
        foreach ($this->bundles as $bundleName => $bundleObject) {
            $result[$bundleName] = [
                'class' => get_class($bundleObject),
                'name' => $bundleObject->getName(),
                'description' => $bundleObject->getDescription(),
                'require' => $bundleObject->require(),
                'conflict' => $bundleObject->conflict(),
                'config' => $bundleObject->config()
            ];
        }
        return $result;
    }

}
