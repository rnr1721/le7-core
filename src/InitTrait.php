<?php

declare(strict_types=1);

namespace Core;

use Core\Interfaces\Config;
use Core\Interfaces\ContainerFactory;
use Core\Interfaces\ListenerProvider;
use Psr\Container\ContainerInterface;
use \Exception;

trait InitTrait
{

    /**
     * Topology of project dirs and production state
     * @var array
     */
    protected array $topology = [];

    /**
     * Configuration manager
     * @var Config|null
     */
    protected ?Config $config = null;

    /**
     * PSR Container
     * @var ContainerInterface|null
     */
    protected ?ContainerInterface $container = null;

    /**
     * Container factory that can create container outside the core
     * @var ContainerFactory
     */
    protected ContainerFactory $containerFactory;

    /**
     * Production status
     * @var bool
     */
    protected bool $isProduction = false;

    public function __construct(
            ContainerFactory $containerFactory,
            Topology $topology
    )
    {

        $this->setErrorSettings();

        $this->containerFactory = $containerFactory;
        $this->topology = $topology->export();

        $this->isProduction = $topology->getIsProduction();
        $this->container = $this->containerFactory->getContainer($this->isProduction);
        $this->config = $this->getConfig($this->container);
        $this->setTimezone();

        $this->container->get(ListenerProvider::class);
    }

    /**
     * This method make ready-for-use configuration manager
     * with dynamically created options current theme, production status and
     * locations of project directories
     * @param ContainerInterface $container PSR container
     * @return Config
     */
    private function getConfig(ContainerInterface $container): Config
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        $config->registerParam('loc', $this->topology);
        $config->registerParam('isProduction', $config->bool('isProduction'));
        $config->applyFilter('current_theme', $config->string('theme') ?? 'Main');
        $config->applyFilter('base', $config->string('loc.base') ?? '');
        $config->applyFilter('var', $config->string('loc.var') ?? '');
        $config->applyFilter('ds', DIRECTORY_SEPARATOR);
        return $config;
    }

    /**
     * Set current timezone from config
     * @return void
     * @throws Exception
     */
    private function setTimezone(): void
    {
        if ($this->config === null) {
            throw new Exception("Init::setTimezone() Please set config first");
        }
        date_default_timezone_set($this->config->string('timezone') ?? 'Europe/Kiev');
    }

    /**
     * Set PHP error reporting options
     * @return void
     */
    private function setErrorSettings(): void
    {
        // Set errors reporting
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
    }

    /**
     * Get PSR container
     * @return ContainerInterface
     * @throws Exception
     */
    private function getContainer(): ContainerInterface
    {
        if (!$this->container instanceof ContainerInterface) {
            throw new Exception("Container not initialized");
        }
        return $this->container;
    }

}
