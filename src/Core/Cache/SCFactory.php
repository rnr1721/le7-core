<?php

namespace le7\Core\Cache;

use Psr\SimpleCache\CacheInterface;
use le7\Core\Cache\Adapters\CacheMemoryAdapter;
use le7\Core\Cache\Adapters\CacheSessionAdapter;
use le7\Core\Cache\Adapters\CacheFileAdapter;
use le7\Core\Cache\Adapters\CacheMemcacheAdapter;
use le7\Core\Cache\Adapters\CacheMemcachedAdapter;

class SCFactory {

    public function getFileCache($folder): CacheInterface {
        $adapter = new CacheFileAdapter($folder);
        return new SimpleCache($adapter);
    }

    public function getMemcache(string $host = "127.0.0.1", int $port = 11211): CacheInterface {
        $memcache = new \Memcache;
        $memcache->connect($host, $port) or die('Could not connect');
        $adapter = new CacheMemcacheAdapter($memcache);
        return new SimpleCache($adapter);
    }

    public function getMemcached(string $host = "127.0.0.1", int $port = 11211): CacheInterface {
        $memcached = new \Memcached;
        $memcached->addServer($host, $port);
        $adapter = new CacheMemcachedAdapter($memcached);
        return new SimpleCache($adapter);
    }

    Public function getMemory(): CacheInterface {
        $adapter = new CacheMemoryAdapter();
        return new SimpleCache($adapter);
    }

    public function getSession(): CacheInterface {
        $adapter = new CacheSessionAdapter();
        return new SimpleCache($adapter);
    }

}
