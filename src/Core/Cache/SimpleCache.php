<?php

namespace App\Core\Cache;

use Psr\SimpleCache\CacheInterface;
use \DateInterval;

class SimpleCache implements CacheInterface {

    private SCAdapterInterface $adapter;

    public function __construct(SCAdapterInterface $adapter) {
        $this->adapter = $adapter;
    }

    public function clear(): bool {
        return $this->adapter->clear();
    }

    public function delete(string $key): bool {
        return $this->adapter->delete($key);
    }

    public function deleteMultiple($keys): bool {
        return $this->adapter->deleteMultiple($keys);
    }

    public function get(string $key, mixed $default = null): mixed {
        return $this->adapter->get($key, $default);
    }

    public function getMultiple($keys, mixed $default = null): iterable {
        return $this->adapter->getMultiple($keys, $default);
    }

    public function has(string $key): bool {
        return $this->adapter->has($key);
    }

    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool {
        return $this->adapter->set($key, $value, $ttl);
    }

    public function setMultiple(iterable $values, null|int|DateInterval $ttl = null): bool {
        return $this->adapter->setMultiple($values, $ttl);
    }

}
