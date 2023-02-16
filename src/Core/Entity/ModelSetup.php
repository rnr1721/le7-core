<?php

namespace le7\Core\Entity;

use le7\Core\Model;
use le7\Core\Helpers\ValidationHelperFactory;
use le7\Core\Entity\Rules\RulesClass;
use le7\Core\Entity\Single\EntitySingleFactory;
use RedBeanPHP\BeanHelper\SimpleFacadeBeanHelper;

class ModelSetup {

    public EntitySingleFactory $entityFactory;
    public ValidationHelperFactory $validationFactory;

    public function __construct(ValidationHelperFactory $validationFactory, EntitySingleFactory $entitySingleFactory) {
        $this->validationFactory = $validationFactory;
        $this->entityFactory = $entitySingleFactory;
    }

    public function prepareModels() {
        SimpleFacadeBeanHelper::setFactoryFunction(function ($name) {
            /** @var Model $model */
            $model = new $name();
            $rules = new RulesClass($model->getClassName());
            $model->init($this->validationFactory->getValidationHelper(), $rules, $this->entityFactory);
            return $model;
        });
    }

}
