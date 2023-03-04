<?php

declare(strict_types=1);

namespace App\Core\ErrorHandling;

use App\Core\Config\ConfigInterface;
use App\Core\Config\TopologyFsInterface;

abstract class ErrorToMain
{

    protected ConfigInterface $config;
    protected TopologyFsInterface $topology;

    public function __construct(ConfigInterface $config, TopologyFsInterface $topology) {
        $this->config = $config;
        $this->topology = $topology;
    }

}
