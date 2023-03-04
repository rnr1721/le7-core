<?php

namespace App\Core\Helpers;

class ValidationHelperFactory {

    public function getValidationHelper() : ValidationHelperInterface {
        return new ValidationHelper();
    }
    
}
