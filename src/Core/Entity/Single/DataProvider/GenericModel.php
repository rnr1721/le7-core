<?php

namespace App\Core\Entity\Single\DataProvider;

use App\Core\Entity\Single\EntitySingleFactory;
use App\Core\Entity\Rules\RulesInterface;
use App\Core\Helpers\ValidationHelperInterface;
use App\Core\Helpers\ValidationHelperFactory;
use App\Core\Entity\Single\EntitySingleInterface;
use App\Core\Entity\Single\SingleDataProviderInterface;

class GenericModel implements SingleDataProviderInterface {

    use DataProviderTrait;

    // It need to be called "bean"
    protected array $bean = [
        'id' => 0
    ];
    protected array $errors = [];
    protected array $rulesArray;
    protected RulesInterface $rules;
    protected ValidationHelperInterface $validator;
    protected EntitySingleFactory $entityFactory;

    public function __construct(ValidationHelperFactory $validatorFactory, RulesInterface $rules, EntitySingleFactory $entityFactory) {
        $this->entityFactory = $entityFactory;
        $this->validator = $validatorFactory->getValidationHelper();
        $this->rules = $rules;
        $this->rulesArray = $rules->getRules();
    }

    public function __get(string $name): string|null {
        if (array_key_exists($name, $this->bean)) {
            return $this->bean[$name];
        }
        return null;
    }

    public function __set(string $name, string|int|float $value) {
        $this->bean[$name] = $value;
    }

    public function getEntity(): EntitySingleInterface {
        return $this->entityFactory->getEntitySingle($this->bean, $this->rules);
    }

    public function validate() {
        $this->check_fields_validate();
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function export() {
        return $this->bean;
    }

}
