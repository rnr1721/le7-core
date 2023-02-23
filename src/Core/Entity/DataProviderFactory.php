<?php

declare(strict_types=1);

namespace le7\Core\Entity;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;
use le7\Core\Entity\Rules\RulesInterface;
use le7\Core\Helpers\ValidationHelperFactory;
use le7\Core\Entity\Single\EntitySingleFactory;
use le7\Core\Entity\Single\DataProvider\GenericModel;
use le7\Core\Entity\Group\EntityGroupFactory;
use le7\Core\Entity\Rules\RulesClass;
use le7\Core\Database\DatabaseConnectionInterface;
use le7\Core\Entity\Group\DataProvider\BeansProvider;
use le7\Core\Entity\Group\DataProvider\BeansPaginatedProvider;

class DataProviderFactory {

    private DatabaseConnectionInterface $dbConnection;

    public function __construct(DatabaseConnectionInterface $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function getGroupFromBean(string $providerRules): BeansProvider {
        $rules = $this->getRules($providerRules);
        return new BeansProvider($this->dbConnection, $rules, $this->getEntityGroupFactory());
    }

    public function getGroupFromBeanPaginated(string $providerRules) {
        $rules = $this->getRules($providerRules);
        return new BeansPaginatedProvider($this->dbConnection, $rules, $this->getEntityGroupFactory());
    }

    public function getSingleGeneric(string $providerRules) {
        $rules = $this->getRules($providerRules);
        return new GenericModel($this->getValidationFactory(), $rules, $this->getEntitySingleFactory());
    }

    public function getSingleBean(string $providerRules): OODBBean {
        return R::dispense($providerRules);
    }

    private function getEntityGroupFactory(): EntityGroupFactory {
        return new EntityGroupFactory($this->getValidationFactory());
    }

    private function getEntitySingleFactory(): EntitySingleFactory {
        return new EntitySingleFactory($this->getValidationFactory());
    }

    private function getRules(string $providerRules): RulesInterface {
        return new RulesClass($providerRules);
    }

    private function getValidationFactory() : ValidationHelperFactory {
        return new ValidationHelperFactory();
    }
    
}
