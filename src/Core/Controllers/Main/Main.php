<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\GlobalModule;
use le7\Core\Cache\SCFactory;
use le7\Core\GlobalEnvironment;
use le7\Custom\UserGlobalLibrary;
use le7\Custom\UserHelpersLibrary;
use le7\Core\Config\UserConfigInterface;
use le7\Core\Database\Database;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Locales\Locales;
use le7\Core\Locales\Translate;
use le7\Core\ErrorHandling\ErrorLog;
use le7\Core\Messages\MessageCollectionInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;

/**
 * @property GlobalEnvironment $env
 * @property MessageCollectionInterface $messages
 * @property Translate $translate
 * @property Locales $locales
 * @property LoggerInterface $ulog
 * @property ErrorLog $log
 * @property SCFactory $cacheFactory
 * @property CacheInterface $cache
 * @property ConfigInterface $config
 * @property TopologyFsInterface $topologyFs
 * @property UserGlobalLibrary $ulib
 * @property UserConfigInterface $uconfig
 * @property Database $db
 * @property UserHelpersLibrary $helpers
 */
class Main extends GlobalModule {

    public function trigger() {
        return array();
    }

}
