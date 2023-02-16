<?php

namespace le7\Core\ClassLibraries;

use le7\Core\ErrorHandling\ErrorLog;

class LibraryUser {
    
    protected ErrorLog $log;
    
    public function __construct(ErrorLog $errorLog) {
        $this->log = $errorLog;
    }
    
}
