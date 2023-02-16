<?php

namespace le7\Core\Cache;

use le7\Core\Config\TopologyFsInterface;
use le7\Core\Config\ConfigInterface;
use Psr\SimpleCache\CacheInterface;

class SCFactoryLe {

    private ConfigInterface $config;
    private TopologyFsInterface $topologyFs;
    private SCFactory $scfactory;
    
    public function __construct(SCFactory $scFactory, TopologyFsInterface $topologyFs, ConfigInterface $config) {
        $this->scfactory = $scFactory;
        $this->topologyFs = $topologyFs;
        $this->config = $config;
    }
    
    public function getFileCache(): CacheInterface {
        return $this->scfactory->getFileCache($this->topologyFs->getObjectCachePath());
    }

    public function getMemcache(): CacheInterface {
        $config = explode(':', $this->config->getMemcacheConfig());
        return $this->scfactory->getMemcache($config[0], intval($config[1]));
    }

    public function getMemcached(): CacheInterface {
        $config = explode(':', $this->config->getMemcachedConfig());
        return $this->scfactory->getMemcached($config[0], intval($config[1]));
    }

    Public function getMemory(): CacheInterface {
        return $this->scfactory->getMemory();
    }

    public function getSession(): CacheInterface {
        return $this->scfactory->getSession();
    }
    
}
