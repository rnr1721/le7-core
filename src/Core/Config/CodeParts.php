<?php

declare(strict_types=1);

namespace App\Core\Config;

use Psr\SimpleCache\CacheInterface;
use function \file_exists,
             \file_get_contents,
             \file_put_contents;

class CodeParts {

    private CacheInterface $cache;
    private string $cachedName = 'code_parts';
    private array $parts = array();

    public function __construct(CacheInterface $cache) {
        $this->cache = $cache;
        if ($cache->has($this->cachedName)) {
            $this->parts = $cache->get($this->cachedName);
        }
    }

    public function register(string $partName, string $file, bool $prod = true): bool {
        if (!$prod) {
            $this->parts[$partName] = '<!-- Statistics assets here -->';
            return true;
        }
        if (!array_key_exists($partName, $this->parts)) {
            if (!file_exists($file)) {
                file_put_contents($file, '<!-- Statistics assets here -->');
            }
            $result = file_get_contents($file);
            $this->parts[$partName] = $result;
            $this->cache->set($this->cachedName, $this->parts);
            return true;
        }
        return false;
    }

    public function get(string $partName, string|null $default = null): string|null {
        if (isset($this->parts[$partName])) {
            return $this->parts[$partName];
        }
        return $default;
    }

    public function __get(string $partName): string|null {
        $result = $this->get($partName);
        if ($result) {
            return $result;
        }
        return null;
    }

}
