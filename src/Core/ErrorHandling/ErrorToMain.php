<?php

declare(strict_types=1);

namespace le7\Core\ErrorHandling;

use le7\Core\Config\ConfigInterface;
use le7\Core\Config\TopologyFsInterface;

abstract class ErrorToMain
{

    protected ConfigInterface $config;
    protected TopologyFsInterface $topology;

    public function __construct(ConfigInterface $config, TopologyFsInterface $topology) {
        $this->config = $config;
        $this->topology = $topology;
    }

}
