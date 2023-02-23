<?php

namespace le7\Core\ClassLibraries;

use le7\Core\ErrorHandling\ErrorLogInterface;

class LibraryUser {
    
    protected ErrorLogInterface $log;
    
    public function __construct(ErrorLogInterface $errorLog) {
        $this->log = $errorLog;
    }
    
}
