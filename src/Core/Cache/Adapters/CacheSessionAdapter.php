<?php

namespace le7\Core\Cache\Adapters;

use le7\Core\Cache\SCAdapterTrait;
use le7\Core\Cache\SCAdapterInterface;
use \DateInterval;

use function time;

class CacheSessionAdapter implements SCAdapterInterface {

    use SCAdapterTrait;

    private string $sessionKey = '_lesc';

    public function __construct() {
        $_SESSION[$this->sessionKey] = array();
    }

    public function clear(): bool {
        $_SESSION[$this->sessionKey] = array();
        return true;
    }

    public function delete(string $key): bool {
        unset($_SESSION[$this->sessionKey][$key]);
        return true;
    }

    public function get(string $key, mixed $default = null): mixed {
        if (isset($_SESSION[$this->sessionKey][$key])) {
            $ttl = $_SESSION[$this->sessionKey][$key]['ttl'] ?? 0;
            if ($ttl === null || $ttl > time()) {
                return $_SESSION[$this->sessionKey][$key];
            }
            unset($_SESSION[$this->sessionKey][$key]);
        }
        return $default;
    }

    public function has(string $key): bool {
        if ($this->get($key)) {
            return true;
        }
        return false;
    }

    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool {
        $ttlFinal = $this->processTTL($ttl);
        $data = array(
            'value' => $value,
            'ttl' => $ttlFinal ? time() + $ttlFinal : null
        );
        $_SESSION[$this->sessionKey][$key] = $data;
        return true;
    }

}
