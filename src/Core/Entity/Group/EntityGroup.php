<?php

declare(strict_types=1);

namespace App\Core\Entity\Group;

use App\Core\Entity\Rules\RulesInterface;
use App\Core\Helpers\ValidationHelperInterface;
use App\Core\Helpers\ValidationHelperFactory;

class EntityGroup implements EntityGroupInterface {

    protected array $errors = array();
    protected ValidationHelperFactory $validatorFactory;
    protected RulesInterface $rulesProvider;
    protected ValidationHelperInterface $validator;
    protected array $rules;
    protected array|object $data;
    protected array $info;

    public function __construct(
            ValidationHelperFactory $validatorFactory,
            array $data,
            RulesInterface $rulesProvider,
            array $info = [],
            array $errors = []
    ) {
        $this->validator = $validatorFactory->getValidationHelper();
        $this->rulesProvider = $rulesProvider;
        $this->rules = $rulesProvider->getRules();
        $this->data = $this->assembly($data);
        $this->info = $info;
        $this->errors = $errors;
    }

    private function assembly(array $data): array {

        $pRes = [];
        foreach ($data as $item) {
            // if is bean - convert to array. Bad perfomance?
            if (is_object($item)) {
                $current = [];
                foreach ($item as $key => $value) {
                    $current[$key] = $value;
                }
                $pRes[] = $current;
            }
        }
        if (!empty($pRes)) {
            $data = $pRes;
        }

        return $data ?? [];
    }

    private function validate(): bool {
        foreach ($this->data as $current) {
            foreach ($current as $field => $value) {
                if (!empty($this->rules[$field]['validate'])) {
                    $rules = $this->rules[$field]['validate'];
                    $label = $this->rules[$field]['label'];
                    $this->validator->setFullRule($field, $value, $rules, $label);
                }
            }
        }
        return $this->validator->validate();
    }

    public function getData(bool $validate = false): array|object {

        if (empty($this->data)) {
            return [];
        }
        if ($validate) {
            $this->validate();
            $messages = $this->validator->getMessages();
            foreach ($messages as $message) {
                $this->errors[] = $message;
            }
            return [];
        } else {
            return $this->data;
        }

        return $this->data;
    }

    public function getInfo(): array {
        return $this->info;
    }

    public function getRules(): array {
        return $this->rules;
    }

    public function count(): int {
        if (is_array($this->data)) {
            return $this->count($this->data);
        }
        return 0;
    }

    public function exists(string $field, mixed $value): bool {
        foreach ($this->data as $item) {
            foreach ($item as $key => $value) {
                if ($key === $field && $value == $value) {
                    return true;
                }
            }
        }
        return false;
    }

    public function find(string $field, mixed $value): array {
        $result = array();
        foreach ($this->data as $item) {
            foreach ($item as $key => $cValue) {
                if ($key === $field && $value == $cValue) {
                    $result[] = $item;
                    break;
                }
            }
        }
        return $result;
    }

    public function findWhere(string $fieldSearch, mixed $valueSearch, string $fieldWhere, mixed $default = null): mixed {
        foreach ($this->data as $item) {
            foreach ($item as $key => $value) {
                if ($key === $fieldSearch && $value == $valueSearch) {
                    if (!empty($item[$fieldWhere])) {
                        return $item[$fieldWhere];
                    }
                }
            }
        }
        return $default;
    }

    public function findValue(mixed $value): array {
        $result = array();
        foreach ($this->data as $item) {
            foreach ($item as $cValue) {
                if ($value == $cValue) {
                    $result[] = $item;
                    break;
                }
            }
        }
        return $result;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function getFieldLabels(): array {
        $result = [];
        foreach ($this->rules as $ruleKey => $ruleValue) {
            $result[$ruleKey] = $ruleValue['label'];
        }
        return $result;
    }

}
