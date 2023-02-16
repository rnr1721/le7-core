<?php

declare(strict_types=1);

namespace le7\Core\Entity\Rules;

use \Exception;

class RulesClass implements RulesInterface {

    private array $availableParams = array(
        'validate', 'label', 'filter', 'check', 'unique', 'default', 'render'
    );
    private array $needParams = array(
        'validate', 'label'
    );
    private array $data;

    private string $model;

    public function __construct(string $model) {
        $this->model = $model;
        $modelClass = $this->findModel($model);
        if (!$modelClass) {
            throw new Exception("RulesClass::findModel() model not exists: " . $model);
        }
        $data = $modelClass::getRules();
        foreach ($data as $item => $value) {
            if (!is_array($value)) {
                throw new Exception("RulesClass::getRules() value must be array: " . $item);
            }
            foreach ($this->needParams as $needParam) {
                if (!array_key_exists($needParam, $value)) {
                    throw new Exception("RulesClass::getRules() param " . $needParam . ' not present in ' . $item);
                }
            }
            foreach ($value as $paramName => $paramValue) {
                if (!in_array($paramName, $this->availableParams)) {
                    throw new Exception("RulesClass::getRules() param " . $paramName . ' not native in ' . $item);
                }
            }
        }
        $this->data = $data;
    }

    public function getRules(): array {
        return $this->data;
    }

    private function findModel($model): string|null {
        $className = 'le7\Model\\' . ucfirst($model);
        if (class_exists($className)) {
            return $className;
        }
        return null;
    }

    public function getName(): string {
        return $this->model;
    }

}
