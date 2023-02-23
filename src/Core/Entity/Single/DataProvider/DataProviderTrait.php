<?php

namespace le7\Core\Entity\Single\DataProvider;

use \Exception;

trait DataProviderTrait {

    public function check_fields_validate() {
        $rulesArray = $this->rulesArray;
        // If create record
        if ($this->bean['id'] === 0) {
            foreach ($rulesArray as $field => $value) {
                if (!empty($value['unique'])) {
                    if (method_exists($this, 'check_field_unique')) {
                        $this->check_field_unique($field, $this->bean[$field] ?? '');
                    }
                }
                if (!empty($value['filter'])) {
                    $this->bean[$field] = $this->check_field_filter($field, $this->bean[$field]);
                }
                if (isset($value['default']) && $value['default'] !== null) {
                    $this->bean[$field] = $this->check_field_default($value['default'], $this->bean[$field] ?? '');
                } else {
                    $this->bean[$field] = $this->check_field_default('', $this->bean[$field] ?? '');
                }
                if (!empty($value['check'])) {
                    $this->check_field_own($field, $this->bean[$field]);
                }
                $this->check_field_valid_create($field, $value);
            }
        } else {
            // If update record
            foreach ($this->bean as $field => $value) {
                if (!empty($rulesArray[$field]['readonly'])) {
                    $message = $rulesArray['field']['label'] . _('is read only');
                    throw new Exception($message, E_NOTICE);
                }
                if (!empty($rulesArray[$field]['unique'])) {
                    $this->check_field_unique($field, $value, true);
                }
                if (!empty($rulesArray[$field]['filter'])) {
                    $this->bean[$field] = $this->check_field_filter($field, $value);
                }
                if (!empty($rulesArray[$field]['default'])) {
                    $this->bean[$field] = $this->check_field_default($rulesArray[$field]['default'], $value);
                }
                if (!empty($rulesArray[$field]['check'])) {
                    $this->check_field_own($field, $value);
                }
                if (array_key_exists($field, $rulesArray)) {
                    $this->check_field_valid_update($field, $rulesArray[$field], $value);
                }
            }
        }
        $validated = $this->validator->validate();

        if (!$validated) {
            $messages = $this->validator->getMessages();
            foreach ($messages as $message) {
                $this->errors[] = $message;
            }
            throw new Exception(_("Error in model fields"), E_NOTICE);
        }
    }

    private function check_field_valid_update(string $field, array $rules, string|int|float $value): void {
        if ($rules['validate'] !== '') {
            $rulesString = $rules['validate'];
            $label = $rules['label'];
            $this->validator->setFullRule($field, $value, $rulesString, $label);
        }
    }

    private function check_field_valid_create(string $field, array $ruleItems): void {
        $rules = $ruleItems['validate'];
        $label = $ruleItems['label'];
        $cValue = $this->bean[$field] ?? '';
        $this->validator->setFullRule($field, $cValue, $rules, $label);
    }

    private function check_field_default(int|string|float $valueDefault, string|int|float $value): string|int|float {
        if ($value === '' || $value === null) {
            return $valueDefault;
        }
        return $value;
    }

    private function check_field_own(string $field, string $value): void {
        $action = 'check_' . $field;
        if (method_exists($this, $action)) {
            $this->{$action}($value);
        } else {
            $message = "Model::check_field_own() method not exists:" . $action . ' in ' . $this->getClassName();
            $this->errors[] = $message;
            throw new Exception($message);
        }
    }

    private function check_field_filter(string $field, mixed $value): string {
        $action = 'filter_' . $field;
        if (method_exists($this, $action)) {
            return $this->{$action}($value);
        } else {
            $message = "Model::check_field_filter() method not exists:" . $action . ' in ' . $this->getClassName();
            $this->errors[] = $message;
            throw new Exception($message);
        }
    }

}
