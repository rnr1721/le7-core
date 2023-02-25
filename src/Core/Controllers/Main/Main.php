<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use RedBeanPHP\OODBBean;
use le7\Core\User\UserInterface;
use le7\Core\Database\DatabaseFactory;
use le7\Custom\UserGlobalLibrary;
use le7\Custom\UserHelpersLibrary;
use le7\Core\Config\UserConfigInterface;
use le7\Core\Database\Database;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Locales\LocalesInterface;
use le7\Core\Locales\TranslateInterface;
use le7\Core\ErrorHandling\ErrorLogInterface;
use le7\Core\Messages\MessageCollectionInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;

/**
 * @property Database $db 
 */
class Main {

    protected UserInterface|OODBBean|null $user = null;
    public DatabaseFactory $dbFactory;
    public ConfigInterface $config;
    public UserConfigInterface $uconfig;
    public TopologyFsInterface $topology;
    public ErrorLogInterface $log;
    public LoggerInterface $ulog;
    public LocalesInterface $locales;
    public TranslateInterface $translate;
    public MessageCollectionInterface $messages;
    public CacheInterface $cache;
    public UserGlobalLibrary $ulib;
    public UserHelpersLibrary $helpers;

    public function trigger() {
        return array();
    }

    public function __get(string $name) {
        switch ($name) {
            case 'db':
                return $this->dbFactory->getDatabase();
            case 'dbConnection':
                return $this->dbFactory->getDatabaseConnection();
            case 'dbConfig':
                return $this->dbFactory->getDatabaseConfig();
            case 'dpFactory':
                return $this->dbFactory->getDataProviderFactory();
            default:
                return null;
        }
    }

}
