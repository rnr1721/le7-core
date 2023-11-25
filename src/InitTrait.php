<?php

declare(strict_types=1);

namespace Core;

use Core\Interfaces\ConfigInterface;
use Core\Interfaces\ContainerFactoryInterface;
use Core\Interfaces\ListenerProviderInterface;
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
     * @var ConfigInterface|null
     */
    protected ?ConfigInterface $config = null;

    /**
     * PSR Container
     * @var ContainerInterface|null
     */
    protected ?ContainerInterface $container = null;

    /**
     * Container factory that can create container outside the core
     * @var ContainerFactoryInterface
     */
    protected ContainerFactoryInterface $containerFactory;

    /**
     * Production status
     * @var bool
     */
    protected bool $isProduction = false;

    public function __construct(
            ContainerFactoryInterface $containerFactory,
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

        $this->container->get(ListenerProviderInterface::class);
    }

    /**
     * This method make ready-for-use configuration manager
     * with dynamically created options current theme, production status and
     * locations of project directories
     * @param ContainerInterface $container PSR container
     * @return ConfigInterface
     */
    private function getConfig(ContainerInterface $container): ConfigInterface
    {

        if (!file_exists($this->topology['config'])) {
            $configSource = $this->topology['base'] . DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR . 'config';

            if (!file_exists($configSource)) {
                echo "Fatal: source config directory not exists: " . $configSource;
                die;
            }

            $this->copyDirectory($configSource, $this->topology['config']);
        }

        /** @var ConfigInterface $config */
        $config = $container->get(ConfigInterface::class);

        $config->registerParam('loc', $this->topology);
        $config->registerParam('isProduction', $this->isProduction);
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

    /**
     * Recursively copies files and directories from the source to the destination.
     *
     * @param string $source      The path to the source directory or file.
     * @param string $destination The path to the destination directory.
     *
     * @return bool              Returns true on successful copying, false on failure.
     */
    private function copyDirectory($source, $destination)
    {
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        $dir = opendir($source);
        if (!$dir) {
            return false;
        }

        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $sourceFile = $source . DIRECTORY_SEPARATOR . $file;
                $destFile = $destination . DIRECTORY_SEPARATOR . $file;

                if (is_dir($sourceFile)) {
                    $this->copyDirectory($sourceFile, $destFile);
                } else {
                    copy($sourceFile, $destFile);
                }
            }
        }

        closedir($dir);
        return true;
    }
}
