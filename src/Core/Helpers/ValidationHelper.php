<?php

namespace le7\Core\Helpers;

use DateTime;
use const FILTER_VALIDATE_EMAIL;
use const FILTER_VALIDATE_URL;

class ValidationHelper implements ValidationHelperInterface {

    protected array $allowedUrl = array('http://', 'https://');
    protected array $messages = array();
    protected array $ignored = array();
    protected array $rules = array();
    protected array $values = array();
    protected array $names = array();

    public function validate(): bool {
        $result = true;
        $actions = $this->getActions();
        foreach ($actions as $field => $cActions) {
            foreach ($cActions as $action => $actionValue) {
                $method = 'validate_' . $action;
                if (method_exists($this, $method)) {
                    if (array_key_exists($field, $this->values)) {
                        if (!$this->{$method}($field, $this->values[$field], $actionValue)) {
                            $result = false;
                        }
                    }
                }
            }
        }
        return $result;
    }

    protected function getActions(): array {
        $actions = array();
        foreach ($this->rules as $field => $rules) {
            $rulesArray = explode('|', $rules);
            foreach ($rulesArray as $ruleUnit) {
                $currentOrerationArray = explode(':', $ruleUnit);
                if (!in_array($field, $this->ignored)) {
                    $actions[$field][$currentOrerationArray[0]] = ($currentOrerationArray[1] ?? '');
                }
            }
        }
        return $actions;
    }

    public function setIgnored(array|string $fields): self {
        if (is_string($fields)) {
            $fieldsArray = explode(',', $fields);
            foreach ($fieldsArray as $field) {
                $this->ignored[] = $field;
            }
        }
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $this->ignored[] = $field;
            }
        }
        return $this;
    }

    public function setValues(array $fields): self {
        foreach ($fields as $field => $value) {
            $this->setValue($field, $value);
        }
        return $this;
    }

    public function setValue(string $field, mixed $value): self {
        $this->values[$field] = $value;
        return $this;
    }

    public function setRules(array $rules) {
        foreach ($rules as $field => $rule) {
            $this->setRule($field, $rule);
        }
    }

    public function setRule(string $field, string $rule): self {
        $fieldArray = explode(',', $field);
        foreach ($fieldArray as $cField) {
            $this->rules[$cField] = $rule;
            if (!array_key_exists($cField, $this->values)) {
                $this->values[$cField] = '';
            }
        }
        return $this;
    }

    public function setFullRule(string $field, mixed $value, string $rule, string $name = ''): self {
        $this->setRule($field, $rule);
        $this->setValue($field, $value);
        if (!empty($name)) {
            $this->setName($field, $name);
        }
        return $this;
    }

    public function setName(string $field, string $name): self {
        $this->names[$field] = $name;
        return $this;
    }

    public function setNames(array $fieldNames): self {
        foreach ($fieldNames as $field => $name) {
            $this->setName($field, $name);
        }
        return $this;
    }

    public function reset(): self {
        $this->rules = array();
        $this->values = array();
        $this->names = array();
        $this->messages = array();
        return $this;
    }

    public function getMessages(): array {
        return $this->messages;
    }

    protected function setError(string $field, string $message, mixed $needle = '') {
        if (array_key_exists($field, $this->names)) {
            $field = $this->names[$field];
        }
        $message = $field . ': ' . $message . $needle;
        if (!in_array($message, $this->messages)) {
            $this->messages[] = $message;
        }
    }

    protected function validate_required(string $field, mixed $value): bool {
        if ($value === '' || $value === []) {
            $this->setError($field, _('is required field') . ' ');
            return false;
        }
        return true;
    }

    protected function validate_notempty(string $field, mixed $value): bool {
        if (empty($value)) {
            $this->setError($field, _('is required field') . ' ');
            return false;
        }
        return true;
    }
    
    protected function validate_min(string $field, string|int|float $value, string|int|float $needle): bool {
        if (!$this->validate_numeric($field, $value)) {
            return false;
        }
        $valueFloat = floatval($value);
        $needleFloat = floatval($needle);
        if ($valueFloat < $needleFloat) {
            $this->setError($field, _('minimal value is') . ' ', $needleFloat);
            return false;
        }
        return true;
    }

    protected function validate_numeric(string $field, string|int|float $value): bool {
        if (!is_numeric($value)) {
            $this->setError($field, _('must be numeric'));
            return false;
        }
        return true;
    }

    protected function validate_max(string $field, string|int|float $value, string|int|float $needle): bool {
        if (!$this->validate_numeric($field, $value)) {
            return false;
        }
        $valueFloat = floatval($value);
        $needleFloat = floatval($needle);
        if ($valueFloat > $needleFloat) {
            $this->setError($field, _('maximal value is') . ' ', $needleFloat);
            return false;
        }
        return true;
    }

    protected function validate_minlength(string $field, string|int|float $value, string|int|float $needle): bool {
        $valueString = strval($value);
        $needleInt = intval($needle);
        if (strlen($valueString) < $needleInt) {
            $this->setError($field, _('minimal length is') . ' ', $needleInt);
            return false;
        }
        return true;
    }

    protected function validate_maxlength(string $field, string|int|float $value, string|int|float $needle): bool {
        $valueString = strval($value);
        $needleInt = intval($needle);
        if (strlen($valueString) > $needleInt) {
            $this->setError($field, _('maximal length is') . ' ', $needleInt);
            return false;
        }
        return true;
    }

    protected function validate_email(string $field, string|int|float $value): bool {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->setError($field, _('not correct email:'), $value);
            return false;
        }
        return true;
    }

    protected function validate_email_dns(string $field, string|int|float $value): bool {
        if (!$this->validate_email($field, $value)) {
            return false;
        }
        $domain = ltrim(stristr($value, '@'), '@') . '.';
        if (function_exists('idn_to_ascii') && defined('INTL_IDNA_VARIANT_UTS46')) {
            $domain = idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46);
        }
        if (checkdnsrr($domain)) {
            return true;
        }
        $this->setError($field, _('domain not correct'), $value);
        return false;
    }

    protected function validate_url(string $field, string|int|float $value): bool {
        foreach ($this->allowedUrl as $prefix) {
            if (str_starts_with($value, $prefix)) {
                return filter_var($value, FILTER_VALIDATE_URL) !== false;
            }
        }
        $this->setError($field, _('not correct') . ': ' . $value);
        return false;
    }

    protected function validate_url_active(string $field, string|int|float $value): bool {
        foreach ($this->allowedUrl as $prefix) {
            if (str_starts_with($value, $prefix)) {
                $host = parse_url(strtolower($value), PHP_URL_HOST);
                if (checkdnsrr($host, 'A') || checkdnsrr($host, 'AAAA') || checkdnsrr($host, 'CNAME')) {
                    return true;
                }
            }
        }
        $this->setError($field, _('not active') . ': ', $value);
        return false;
    }

    protected function validate_date(string $field, mixed $value): bool {
        if ($value instanceof DateTime) {
            $isDate = true;
        } else {
            $isDate = strtotime($value) !== false;
        }
        if (!$isDate) {
            $this->setError($field, _('incorrect date') . ': ', $value);
            return false;
        }
        return true;
    }

    protected function validate_date_format(string $field, mixed $value, string $needle): bool {
        $parsed = date_parse_from_format($needle, $value);
        $result = $parsed['error_count'] === 0 && $parsed['warning_count'] === 0;
        if (!$result) {
            $this->setError($field, _('correct date format') . ': ', $needle);
        }
        return true;
    }

    protected function validate_date_before(string $field, mixed $value, string $needle): bool {
        $vtime = ($value instanceof DateTime) ? $value->getTimestamp() : strtotime($value);
        echo $value;
        $ptime = (strtotime($needle));
        if ($vtime < $ptime) {
            return true;
        }
        $this->setError($field, _('The date must not exceed') . ' ', $needle);
        return false;
    }

    protected function validate_date_after(string $field, mixed $value, string $needle): bool {
        $vtime = ($value instanceof DateTime) ? $value->getTimestamp() : strtotime($value);
        $ptime = (strtotime($needle));
        if ($vtime > $ptime) {
            return true;
        }
        $this->setError($field, _('The date must not be below') . ' ', $needle);
        return false;
    }

    protected function validate_boolean(string $field, mixed $value): bool {
        if (is_bool($value)) {
            return true;
        }
        $this->setError($field, _('must be boolean'));
        return false;
    }

}
