<?php

declare(strict_types=1);

namespace le7\Core;

use le7\Core\Entity\DataProviderFactory;
use le7\Core\Cache\SCFactory;
use le7\Core\GlobalEnvironment;
use le7\Custom\UserGlobalLibrary;
use le7\Custom\UserHelpersLibrary;
use le7\Core\Config\UserConfigInterface;
use le7\Core\Database\Database;
use le7\Core\Database\DatabaseConnection;
use le7\Core\Config\DbConfig;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Locales\Locales;
use le7\Core\Locales\Translate;
use le7\Core\ErrorHandling\ErrorLog;
use le7\Core\Messages\MessageCollectionInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;

/**
 * @property DataProviderFactory $dpFactory
 * @property Database $db
 * @property DatabaseConnection $dbConnection
 * @property DbConfig $dbConfig
 */
abstract class GlobalModule {

    public GlobalEnvironment $env;
    public MessageCollectionInterface $messages;
    public Translate $translate;
    public Locales $locales;
    public LoggerInterface $ulog;
    public ErrorLog $log;
    public SCFactory $cacheFactory;
    public CacheInterface $cache;
    public ConfigInterface $config;
    public UserConfigInterface $uconfig;
    public TopologyFsInterface $topologyFs;
    public UserGlobalLibrary $ulib;
    public UserHelpersLibrary $helpers;

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
