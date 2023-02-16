<?php

namespace le7\Core\Cache\Adapters;

use le7\Core\Cache\SCAdapterTrait;
use le7\Core\Cache\SCAdapterInterface;
use \DateInterval;
use function time,
             file_exists,
             is_dir,
             is_writable,
             is_file,
             unlink,
             unserialize,
             serialize,
             file_get_contents,
             file_put_contents;

class CacheFileAdapter implements SCAdapterInterface {

    use SCAdapterTrait;

    private string $extension;
    private string $folder;

    public function __construct(string $folder, string $extension = "cache") {
        $this->extension = $extension;
        $this->folder = $folder;
        if (!is_dir($folder)) {
            throw new CacheException(_("You need to set correct cache dir"));
        }
        if (!is_writable($folder)) {
            throw new CacheException(_("Wrong write permissions of cache folder:") . ' ' . $this->folder);
        }
    }

    public function clear(): bool {
        $files = glob($this->folder . DIRECTORY_SEPARATOR . '/*.' . $this->extension);
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
            return true;
        }
        return false;
    }

    public function delete(string $key): bool {
        $filename = $this->getPath($key);
        if (file_exists($filename)) {
            unlink($filename);
            return true;
        }
        return false;
    }

    public function get(string $key, mixed $default = null): mixed {
        $filename = $this->getPath($key);
        if (file_exists($filename)) {
            $itemRaw = file_get_contents($filename);
            $item = unserialize($itemRaw);
            if ($item['ttl'] === null || $item['ttl'] > time()) {
                return $item['value'];
            }
            unlink($filename);
        }
        return $default;
    }

    public function has(string $key): bool {
        $filename = $this->getPath($key);
        if (file_exists($filename)) {
            return true;
        }
        return false;
    }

    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool {
        $ttlFinal = $this->processTTL($ttl);
        $filename = $this->getPath($key);
        $data = [
            'value' => $value,
            'ttl' => ($ttlFinal === null ? null : time() + $ttlFinal)
        ];
        file_put_contents($filename, serialize($data));
        if (file_exists($filename)) {
            return true;
        }
        return false;
    }

    private function getPath($key): string {
        return $this->folder . DIRECTORY_SEPARATOR . $key . '.' . $this->extension;
    }

}
