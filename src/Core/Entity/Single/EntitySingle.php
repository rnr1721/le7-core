<?php

declare(strict_types=1);

namespace le7\Core\Entity\Single;

use le7\Core\Helpers\ValidationHelperFactory;
use le7\Core\Entity\Rules\RulesInterface;
use le7\Core\Helpers\ValidationHelperInterface;
use \Exception;

class EntitySingle implements EntitySingleInterface {

    protected array $errors = [];
    protected RulesInterface $rulesProvider;
    protected GroupDataProviderInterface $dataProvider;
    protected GroupDataProcessorInterface $dataProcessor;
    protected ValidationHelperInterface $validator;
    protected array $rules;
    protected array|object $data;
    protected bool $validate = true;

    public function __construct(
            ValidationHelperFactory $validatorFactory,
            array $data,
            RulesInterface $rulesProvider,
            array $errors = []
    ) {
        $this->validator = $validatorFactory->getValidationHelper();
        $this->rulesProvider = $rulesProvider;
        $this->rules = $rulesProvider->getRules();
        $this->data = $data;
        $this->errors = $errors;
    }

    public function getData(): array {
        return $this->data;
    }

    public function getRules(): array {
        return $this->rules;
    }

    public function getFieldLabels(): array {
        $result = [];
        foreach ($this->rules as $ruleKey => $ruleValue) {
            $result[$ruleKey] = $ruleValue['label'];
        }
        return $result;
    }

    public function getValue(string $field): string|int|float|null {
        if (array_key_exists($field, $this->data)) {
            return $this->data[$field];
        }
        return null;
    }

    public function isDefault(string $field): bool {
        if (array_key_exists($field, $this->data)) {
            if (isset($this->rules[$field]['default'])) {
                $default = $this->rules[$field]['default'];
            } else {
                $default = '';
            }
            if ($default === $this->data[$field]) {
                return true;
            }
            return false;
        }
        $message = _("EntitySingle::isDefault() field not exists:") . $field;
        $this->errors[] = $message;
        throw new Exception($message);
    }

    public function getErrors(): array {
        return $this->errors;
    }

}
