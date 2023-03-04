<?php

namespace App\Core\Entity;

use App\Core\Model;
use App\Core\Helpers\ValidationHelperFactory;
use App\Core\Entity\Rules\RulesClass;
use App\Core\Entity\Single\EntitySingleFactory;
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
