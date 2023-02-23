<?php

namespace le7\Core\ErrorHandling;

use \Exception;

interface ErrorLogInterface {

    public function callError(Exception $e): void;
}
