<?php

declare(strict_types=1);

namespace Core\Bundles;

use Core\Interfaces\RouteRepositoryInterface;
use Core\Interfaces\LocalesInterface;
use Core\Interfaces\ConfigInterface;
use Core\Interfaces\BundleInterface;
use Core\Interfaces\BundleManagerInterface;
use Core\Routing\RunnerTrait;
use Core\Exceptions\BundleConflictException;
use Core\Exceptions\MissingRequiredBundleException;
use Psr\Container\ContainerInterface;
use \Exception;

class BundleManager implements BundleManagerInterface
{

    use RunnerTrait;

    protected RouteRepositoryInterface $routeRepository;
    protected LocalesInterface $locales;
    protected ContainerInterface $container;
    protected ConfigInterface $config;
    protected array $bundles = [];
    protected array $required = [];
    protected bool $ready = false;
    protected bool $readyConfig = false;

    public function __construct(
            ContainerInterface $container,
            ConfigInterface $config,
            LocalesInterface $locales,
            RouteRepositoryInterface $routeRepository
    )
    {
        $this->container = $container;
        $this->config = $config;
        $this->locales = $locales;
        $this->routeRepository = $routeRepository;
        $this->addBundle(Root::class);
    }

    public function configureAll(): void
    {
        if ($this->readyConfig) {
            throw new Exception('Bundles already configured');
        }
        $this->checkRequired();
        $globalsConfig = [
            'viewDirs' => []
        ];
        /** @var BundleInterface $bundleObject */
        foreach ($this->bundles as $bundleName => $bundleObject) {
            $path = $bundleObject->getPath() . DIRECTORY_SEPARATOR;
            $this->checkConflicts($bundleName);
            $globalsConfig = array_merge_recursive(
                    $globalsConfig,
                    $this->assemblyConfig($bundleObject, $path)
            );
            $this->routeRepository->setRouteCollection($bundleObject->getRoutes());
            $this->locales->addLocale('le7-' . $bundleObject->getName(), $path . 'Locales');
        }
        $this->config->registerParam('globals', $globalsConfig);
        $this->readyConfig = true;
    }

    public function initAll(): void
    {
        if ($this->ready) {
            throw new Exception("Bundles init already completed");
        }
        if (!$this->readyConfig) {
            throw new Exception("You must run configureAll method at first");
        }
        /** @var BundleInterface $bundleObject */
        foreach ($this->bundles as $bundleObject) {
            if (method_exists($bundleObject, 'init')) {
                $this->runAction($bundleObject, 'init');
            }
        }
        $this->ready = true;
    }

    protected function assemblyConfig(BundleInterface $bundle, string $path): array
    {
        $result = [
            'menu' => $bundle->getMenu()
        ];
        if ($bundle->getName() !== 'root') {
            $result['viewDirs'] = [$path . 'View'];
        }
        return $result;
    }

    public function addBundle(string $bundle): self
    {
        if ($this->readyConfig) {
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
        $this->required = array_unique(array_merge($this->required, $bundleObject->getRequired()));
        $this->bundles[$name] = $bundleObject;
        $configName = 'bundles.config.' . $name;
        $configValue = $this->config->array($configName, []) ?? [];
        if (count($configValue) === 0) {
            $this->config->registerParam($configName, $bundleObject->getConfig());
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
                if (in_array($bundleName, $bundleObject->getConflicts())) {
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
                'require' => $bundleObject->getRequired(),
                'conflict' => $bundleObject->getConflicts(),
                'config' => $bundleObject->getConfig()
            ];
        }
        return $result;
    }
}
