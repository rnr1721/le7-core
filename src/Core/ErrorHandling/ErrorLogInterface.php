<?php

namespace App\Core\ErrorHandling;

use \Exception;

interface ErrorLogInterface {

    public function callError(Exception $e): void;
}
