<?php

declare(strict_types=1);

namespace App\Core\Entity\Single;

use App\Core\Helpers\ValidationHelperFactory;
use App\Core\Entity\Rules\RulesInterface;

class EntitySingleFactory {

    private ValidationHelperFactory $validatorFactory;

    public function __construct(ValidationHelperFactory $validatorFactory) {
        $this->validatorFactory = $validatorFactory;
    }

    public function getEntitySingle(array $data, RulesInterface $rules) {
        return new EntitySingle($this->validatorFactory, $data, $rules);
    }

}
