<?php

declare(strict_types=1);

namespace le7\Core\Entity\Single\DataProvider;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;
use le7\Core\Entity\Single\EntitySingleFactory;
use le7\Core\Entity\Rules\RulesInterface;
use le7\Core\Entity\Single\EntitySingleInterface;
use le7\Core\Helpers\ValidationHelperInterface;
use le7\Core\Entity\Single\SingleDataProviderInterface;
use \RedBeanPHP\TypedModel;
use \ReflectionClass;
use Exception;

/**
 * @property OODBBean $bean
 */
abstract class RedbeanModel extends TypedModel implements SingleDataProviderInterface {

    use DataProviderTrait;

    protected array $errors = [];
    protected array $rulesArray;
    protected RulesInterface $rules;
    protected ValidationHelperInterface $validator;
    protected EntitySingleFactory $entityFactory;

    public function init(ValidationHelperInterface $validator, RulesInterface $rules, EntitySingleFactory $entityFactory) {
        $this->entityFactory = $entityFactory;
        $this->validator = $validator;
        $this->rules = $rules;
        $this->rulesArray = $rules->getRules();
    }

    public function update() {
        $this->check_fields_validate();
    }

    private function check_field_unique(string $field, string $value, bool $isUpdate = false) {
        if ($isUpdate === true) {
            $result = R::findOne(lcfirst($this->getClassName()), " $field = ? AND id != ? ", [$value, $this->bean['id']]);
        } else {
            $result = R::findOne(lcfirst($this->getClassName()), " $field = ? ", [$value]);
        }

        if ($result) {
            $message = _('Record with') . ' ' . $field . ' ' . $value . ' ' . _('already exists');
            $this->errors[] = $message;
            throw new Exception($message);
        }
    }

    abstract static public function getRules(): array;

    public function getEntity(): EntitySingleInterface {
        return $this->entityFactory->getEntitySingle($this->bean->export(), $this->rules);
    }

    public function getClassName(): string {
        return (new ReflectionClass($this))->getShortName();
    }

    public function getErrors(): array {
        return $this->errors;
    }

}
