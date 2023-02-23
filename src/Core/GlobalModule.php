<?php

declare(strict_types=1);

namespace le7\Core;

use le7\Core\User\UserInterface;
use le7\Core\Entity\DataProviderFactory;
use le7\Core\Cache\SCFactory;
use le7\Core\GlobalEnvironment;
use le7\Custom\UserGlobalLibrary;
use le7\Custom\UserHelpersLibrary;
use le7\Core\Config\UserConfigInterface;
use le7\Core\Database\Database;
use le7\Core\Database\DatabaseConnectionInterface;
use le7\Core\Config\DbConfig;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Locales\LocalesInterface;
use le7\Core\Locales\TranslateInterface;
use le7\Core\ErrorHandling\ErrorLogInterface;
use le7\Core\Messages\MessageCollectionInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;
use \RedBeanPHP\OODBBean;

/**
 * @property DataProviderFactory $dpFactory
 * @property Database $db
 * @property DatabaseConnectionInterface $dbConnection
 * @property DbConfig $dbConfig
 */
abstract class GlobalModule {

    protected UserInterface|OODBBean|null $user = null;
    protected GlobalEnvironment $env;
    protected MessageCollectionInterface $messages;
    protected TranslateInterface $translate;
    protected LocalesInterface $locales;
    protected LoggerInterface $ulog;
    protected ErrorLogInterface $log;
    protected SCFactory $cacheFactory;
    protected CacheInterface $cache;
    protected ConfigInterface $config;
    protected UserConfigInterface $uconfig;
    protected TopologyFsInterface $topologyFs;
    protected UserGlobalLibrary $ulib;
    protected UserHelpersLibrary $helpers;

    public function __construct(GlobalEnvironment $env) {
        $this->env = $env;
        $this->messages = $env->messages;
        $this->translate = $env->translate;
        $this->locales = $env->locales;
        $this->log = $env->log;
        $this->ulog = $env->ulog;
        $this->cacheFactory = $env->cacheFactory;
        $this->cache = $env->cache;
        $this->config = $env->config;
        $this->uconfig = $env->uconfig;
        $this->topologyFs = $env->topologyFs;
        $this->ulib = $env->ulib;
        $this->helpers = $env->helpers;
        $this->user = $env->user;
        
        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    public function __get(string $name) {
        switch ($name) {
            case 'db':
                return $this->env->dbFactory->getDatabase();
            case 'dbConnection':
                return $this->env->dbFactory->getDatabaseConnection();
            case 'dbConfig':
                return $this->env->dbFactory->getDatabaseConfig();
            case 'dpFactory':
                return $this->env->dbFactory->getDataProviderFactory();
            default:
                return null;
        }
    }

}
