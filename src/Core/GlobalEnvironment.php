<?php

declare(strict_types=1);

namespace le7\Core;

use le7\Core\Database\DatabaseFactory;
use le7\Core\Cache\SCFactory;
use le7\Core\Config\UserConfigInterface;
use le7\Custom\UserHelpersLibrary;
use le7\Custom\UserGlobalLibrary;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Locales\Locales;
use le7\Core\Locales\Translate;
use le7\Core\ErrorHandling\ErrorLog;
use le7\Core\Messages\MessageCollectionInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class GlobalEnvironment {

    public DatabaseFactory $dbFactory;
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

    public function __construct(
            ConfigInterface $config,
            TopologyFsInterface $topology,
            UserConfigInterface $userConfig,
            Locales $locales,
            Translate $translate,
            ErrorLog $errorLog,
            LoggerInterface $ulog,
            SCFactory $cacheFactory,
            CacheInterface $cache,
            UserGlobalLibrary $ulib,
            MessageCollectionInterface $messageCollection,
            UserHelpersLibrary $helpers,
            DatabaseFactory $dbFactory
    ) {
        $this->translate = $translate;
        $this->locales = $locales;
        $this->log = $errorLog;
        $this->ulog = $ulog;
        $this->cache = $cache;
        $this->cacheFactory = $cacheFactory;
        $this->config = $config;
        $this->topologyFs = $topology;
        $this->ulib = $ulib;
        $this->uconfig = $userConfig;
        $this->messages = $messageCollection;
        $this->helpers = $helpers;
        $this->dbFactory = $dbFactory;
    }

}
