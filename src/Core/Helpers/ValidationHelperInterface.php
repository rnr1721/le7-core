<?php


namespace le7\Core\Helpers;


interface ValidationHelperInterface
{
    public function validate(): bool;
    public function setIgnored(array|string $fields) : self;
    public function setValues(array $fields): self;
    public function setValue(string $field, mixed $value): self;
    public function setRules(array $rules);
    public function setRule(string $field, string $rule): self;
    public function setFullRule(string $field, mixed $value, string $rule, string $name = ''): self;
    public function setName(string $field, string $name): self;
    public function setNames(array $fieldNames): self;
    public function reset(): self;
    public function getMessages(): array;
}
