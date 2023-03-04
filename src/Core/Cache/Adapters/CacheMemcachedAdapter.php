<?php

namespace App\Core\Cache\Adapters;

use App\Core\Cache\InvalidArgumentException;
use App\Core\Cache\SCAdapterInterface;
use App\Core\Cache\SCAdapterTrait;
use \Memcached;
use \DateInterval;

class CacheMemcachedAdapter implements SCAdapterInterface {

    use SCAdapterTrait;

    private Memcached $mc;

    public function __construct(Memcached $memcached) {
        $this->mc = $memcached;
        if (empty($this->mc)) {
            throw new CacheException("Memcached not available");
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
        return $this->mc->set($key, $value, $ttlFinal);
    }

    public function getMultiple($keys, mixed $default = null): iterable {
        if (!is_array($keys)) {
            throw new InvalidArgumentException("keys must be array");
        }

        $data = array();

        $result = $this->mc->getMulti($keys);

        foreach ($keys as $key) {
            $data[$key] = $result[$key] ?? $default;
        }

        return $data;
    }

    public function setMultiple(iterable $values, null|int|DateInterval $ttl = null): bool {
        $ttlFinal = $this->processTTL($ttl);
        if ($ttlFinal === null) {
            $ttlFinal = 0;
        }
        return $this->mc->setMulti($values, $ttlFinal);
    }

    public function deleteMultiple($keys): bool {
        $data = $this->mc->deleteMulti($keys);
        $result = true;
        foreach ($data as $item) {
            $current = boolval($item);
            if (!$current) {
                $result = false;
            }
        }
        return $result;
    }

}
