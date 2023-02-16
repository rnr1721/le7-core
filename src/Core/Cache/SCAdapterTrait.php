<?php

namespace le7\Core\Cache;

use \DateInterval;
use \DateTime;

trait SCAdapterTrait {

    public function getMultiple($keys, mixed $default = null): iterable {
        $result = array();
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }
        return $result;
    }

    public function setMultiple(iterable $values, null|int|DateInterval $ttl = null): bool {
        $result = true;
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                $result = false;
            }
        }
        return $result;
    }

    public function deleteMultiple($keys): bool {
        $result = true;
        if (is_iterable($keys)) {
            foreach ($keys as $key) {
                if (!$this->delete($key)) {
                    $result = false;
                }
            }
        }
        return $result;
    }

    public function processTTL(int|null|DateInterval $ttl): int|null {
        if ($ttl instanceof DateInterval) {
            $dt = new DateTime();
            return $dt->setTimestamp(0)->add($ttl)->getTimestamp() - time();
        }
        if ((is_int($ttl) && $ttl > 0) || $ttl === null) {
            return $ttl;
        }
        throw new InvalidArgumentException('Incorrect cache TTL');
    }

}
