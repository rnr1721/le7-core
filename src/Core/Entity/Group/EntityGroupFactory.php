<?php

declare(strict_types=1);

namespace le7\Core\Entity\Group;

use le7\Core\Helpers\ValidationHelperFactory;
use le7\Core\Entity\Rules\RulesInterface;

class EntityGroupFactory {

    private ValidationHelperFactory $validatorFactory;

    public function __construct(ValidationHelperFactory $validatorFactory) {
        $this->validatorFactory = $validatorFactory;
    }

    public function getEntityGroup(array $data, RulesInterface $rules, array $info, array $errors) {
        return new EntityGroup($this->validatorFactory, $data, $rules, $info, $errors);
    }

}
