<?php

declare(strict_types=1);

namespace le7\Core\Controllers\Main;

use le7\Core\GlobalEnvironment;
use le7\Core\Instances\RouteInterface;
use le7\Custom\UserGlobalLibrary;
use le7\Custom\UserHelpersLibrary;
use le7\Core\Config\UserConfigInterface;
use le7\Core\Database\Database;
use Psr\SimpleCache\CacheInterface;
use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;
use le7\Core\Locales\Locales;
use le7\Core\Locales\Translate;
use le7\Core\ErrorHandling\ErrorLog;
use le7\Core\Messages\MessageCollectionInterface;
use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;
use le7\Core\Traits\ConsoleTrait;

/**
 * @property MessageCollectionInterface $messages
 * @property ContainerInterface $container
 * @property Translate $translate
 * @property Locales $locales
 * @property LoggerInterface $ulog
 * @property ErrorLog $log
 * @property CacheInterface $cache
 * @property ConfigInterface $config
 * @property TopologyFsInterface $topologyFs
 * @property UserGlobalLibrary $ulib
 * @property UserConfigInterface $uconfig
 * @property Database $db
 * @property UserHelpersLibrary $helpers
 */
class Cli extends Main {

    public RouteInterface $route;

    use ConsoleTrait;

    public function __construct(GlobalEnvironment $env) {
        parent::__construct($env);
    }
    
}
