<?php

namespace Core\Controller\Console\System;

use Core\Controller\Console\BaseController;
use Core\Interfaces\Config;

class CacheController extends BaseController
{

    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function indexAction(): void
    {
        $this->stdout('Cache directory:' . $this->config->string('loc.cache'));
        $this->stdout('This info      : ./cli cache');
        $this->stdout('Clear cache    : ./cli cache:clear');
    }

    public function clearAction(): void
    {
        $cacheDir = $this->config->string('loc.cache') . DIRECTORY_SEPARATOR;
        $files = glob($cacheDir . '*');
        foreach ($files as $file) {

            if (is_file($file) && $file !== '.' && $file !== '..') {
                unlink($file);
            }
        }
        $this->stdout('Cache cleared');
    }

}
