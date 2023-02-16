<?php

declare(strict_types=1);

namespace le7\Core\Entity\Group;

interface EntityGroupInterface {

    /**
     * Get body of data.
     * @param bool $validate Validate every item in array
     * @return array|object
     */
    public function getData(bool $validate = false): array|object;

    /**
     * Get support info if exists.
     * E.g. in case of pagination it returns pagination data
     * In other cases it return empty array
     * @return array
     */
    public function getInfo(): array;

    /**
     * Get Entity rules,
     * e.g. validation, rendering etc.
     * @return array
     */
    public function getRules(): array;

    /**
     * Get count of data body
     * @return int
     */
    public function count(): int;

    /**
     * if record with field and value exists
     * @param string $field
     * @param string $value
     * @return bool
     */
    public function exists(string $field, string $value): bool;

    /**
     * Search one or more posts where field and value
     * @param string $field Field to find
     * @param mixed $value Value to find
     * @return array
     */
    public function find(string $field, mixed $value): array;

    /**
     * Return value of field where field = X and value = X
     * if not fount it will return $default
     * @param string $fieldSearch Key what we search
     * @param mixed $valueSearch Value what we search
     * @param string $fieldWhere Key where we want get value
     * @param mixed $default Default return value if not found
     * @return mixed
     */
    public function findWhere(string $fieldSearch, mixed $valueSearch, string $fieldWhere, mixed $default = null): mixed;

    /**
     * Return posts where current value present
     * Can return many posts
     * @param mixed $value Search value
     * @return array
     */
    public function findValue(mixed $value): array;

    /**
     * Get errors list
     * @return array
     */
    public function getErrors(): array;
    
    /**
     * Get array of human-readable columns
     * @return array
     */
    public function getFieldLabels(): array;
}
