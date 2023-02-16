<?php

declare(strict_types=1);

namespace le7\Core\Instances;

use le7\Core\Locales\Locales;
use le7\Core\Php;
use le7\Core\EventDispatcher\EventInvoker;
use Psr\Container\ContainerInterface;
use le7\Core\ErrorHandling\ErrorLog;
use le7\Core\Config\ConfigInterface;
use le7\Core\Config\TopologyFsInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

abstract class InstanceAbstract {

    protected Php $php;
    protected Locales $locales;
    protected EventInvoker $eventInvoker;
    protected ContainerInterface $container;
    protected ErrorLog $log;
    protected LoggerInterface $systemLog;
    protected ConfigInterface $config;
    protected TopologyFsInterface $topology;

    /**
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
            ContainerInterface $container,
            ConfigInterface $config,
            TopologyFsInterface $topology,
            LoggerInterface $systemLog,
            ErrorLog $log,
            EventInvoker $eventInvoker,
            Php $php,
            Locales $locales
    ) {
        $this->container = $container;
        $this->config = $config;
        $this->topology = $topology;
        $this->systemLog = $systemLog;
        $this->log = $log;
        $this->eventInvoker = $eventInvoker;
        $this->php = $php;
        $this->locales = $locales;
    }

}
