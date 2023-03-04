<?php

namespace App\Core\Cache\Adapters;

use App\Core\Cache\SCAdapterInterface;
use App\Core\Cache\SCAdapterTrait;
use DateInterval;

class CacheMemoryAdapter implements SCAdapterInterface {

    use SCAdapterTrait;

    private array $cache = [];

    public function clear(): bool {
        $this->cache = [];
    }

    public function delete(string $key): bool {
        unset($this->cache[$key]);
    }

    public function get(string $key, mixed $default = null): mixed {
        if (isset($this->cache[$key])) {
            $ttl = $this->cache[$key]['ttl'];
            if ($ttl === null || $ttl > time()) {
                return $this->cache[$key]['value'];
            }
            unset($this->cache[$key]);
        }
        return $default;
    }

    public function has(string $key): bool {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }
        return false;
    }

    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool {
        $ttlFinal = $this->processTTL($ttl);
        $this->cache[$key] = array(
            'value' => $value,
            'ttl' => $ttlFinal ? time() + $ttlFinal : null
        );
        return true;
    }

}
