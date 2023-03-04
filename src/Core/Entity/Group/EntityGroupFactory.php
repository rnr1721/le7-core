<?php

declare(strict_types=1);

namespace App\Core\Entity\Group;

use App\Core\Helpers\ValidationHelperFactory;
use App\Core\Entity\Rules\RulesInterface;

class EntityGroupFactory {

    private ValidationHelperFactory $validatorFactory;

    public function __construct(ValidationHelperFactory $validatorFactory) {
        $this->validatorFactory = $validatorFactory;
    }

    public function getEntityGroup(array $data, RulesInterface $rules, array $info, array $errors) {
        return new EntityGroup($this->validatorFactory, $data, $rules, $info, $errors);
    }

}
