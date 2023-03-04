<?php

namespace App\Core\Cache\Adapters;

use App\Core\Cache\SCAdapterInterface;
use App\Core\Cache\SCAdapterTrait;
use \Memcache;
use \DateInterval;

class CacheMemcacheAdapter implements SCAdapterInterface {

    use SCAdapterTrait;

    private Memcache $mc;

    public function __construct(Memcache $memcached) {
        $this->mc = $memcached;
        if (empty($this->mc)) {
            throw new CacheException("Memcache not available");
        }
    }

    public function clear(): bool {
        return $this->mc->flush();
    }

    public function delete(string $key): bool {
        return $this->mc->delete($key);
    }

    public function get(string $key, mixed $default = null): mixed {
        $result = $this->mc->get($key);
        if ($result === false) {
            return $default;
        }
        return $result;
    }

    public function has(string $key): bool {
        $result = $this->mc->get($key);
        if ($result === false) {
            return false;
        }
        return true;
    }

    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool {
        $ttlFinal = $this->processTTL($ttl);
        if (!$ttlFinal) {
            $ttlFinal = 0;
        }
        return $this->mc->set($key, $value, 0,$ttlFinal);
    }

}
