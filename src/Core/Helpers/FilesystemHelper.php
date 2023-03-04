<?php

namespace App\Core\Helpers;

class FilesystemHelper {

    public function recursiveCopyFolder(string $source, string $dest): bool {
        if (!file_exists($source)) {
            return false;
        }
        mkdir($dest, 0755);
        foreach (
                $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
            } else {
                copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathname());
            }
        }
        return true;
    }

    public function recursiveRemoveDirectory(string $dir): bool {

        if (!file_exists($dir)) {
            return false;
        }

        if (is_file($dir)) {
            unlink($dir);
        } elseif (is_dir($dir)) {
            $scan = glob(rtrim($dir, '/') . '/{,.}[!.,!..]*', GLOB_MARK | GLOB_BRACE);
            foreach ($scan as $path) {
                $this->recursiveRemoveDirectory($path);
            }
            return rmdir($dir);
        }

        return true;
    }

    public function downloadFilePhp(string $source, string $dest): bool {
        $hRead = fopen($source, 'rb');
        $hWrite = fopen($dest, 'w+b');
        if (!$hRead || !$hWrite) {
            return false;
        }

        while (!feof($hRead)) {
            if (fwrite($hWrite, fread($hRead, 4096)) === FALSE) {
                return false;
            }
            echo ' ';
            flush();
        }

        fclose($hRead);
        fclose($hWrite);

        return true;
    }

    public function downloadFileCurl(string $source, string $dest, string|null $userAgent = null, string|null $referrer = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        if ($userAgent !== null) {
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        }
        if ($referrer !== null) {
            curl_setopt($ch, CURLOPT_REFERER, $referrer);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        // the following lines write the contents to a file in the same directory (provided permissions etc)
        $fp = fopen($dest, 'w');
        fwrite($fp, $result);
        fclose($fp);
    }

}
