<?php

declare(strict_types=1);

namespace le7\Core\Entity\Single;

use le7\Core\Helpers\ValidationHelperFactory;
use le7\Core\Entity\Rules\RulesInterface;

class EntitySingleFactory {

    private ValidationHelperFactory $validatorFactory;

    public function __construct(ValidationHelperFactory $validatorFactory) {
        $this->validatorFactory = $validatorFactory;
    }

    public function getEntitySingle(array $data, RulesInterface $rules) {
        return new EntitySingle($this->validatorFactory, $data, $rules);
    }

}
