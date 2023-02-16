<?php

namespace le7\Core\Helpers;

class ValidationHelperFactory {

    public function getValidationHelper() : ValidationHelperInterface {
        return new ValidationHelper();
    }
    
}
