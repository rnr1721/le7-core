<?php

namespace App\Core\Cache;

use Psr\SimpleCache\CacheException;
use \Exception;

class CacheException extends Exception implements CacheException {
    
}
