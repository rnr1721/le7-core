<?php

namespace le7\Core\Entity\Single\DataProvider;

use le7\Core\Entity\Single\EntitySingleFactory;
use le7\Core\Entity\Rules\RulesInterface;
use le7\Core\Helpers\ValidationHelperInterface;
use le7\Core\Helpers\ValidationHelperFactory;
use le7\Core\Entity\Single\EntitySingleInterface;
use le7\Core\Entity\Single\SingleDataProviderInterface;

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
