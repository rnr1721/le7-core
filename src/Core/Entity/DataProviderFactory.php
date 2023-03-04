<?php

declare(strict_types=1);

namespace App\Core\Entity;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;
use App\Core\Entity\Rules\RulesInterface;
use App\Core\Helpers\ValidationHelperFactory;
use App\Core\Entity\Single\EntitySingleFactory;
use App\Core\Entity\Single\DataProvider\GenericModel;
use App\Core\Entity\Group\EntityGroupFactory;
use App\Core\Entity\Rules\RulesClass;
use App\Core\Database\DbConnInterface;
use App\Core\Entity\Group\DataProvider\BeansProvider;
use App\Core\Entity\Group\DataProvider\BeansPaginatedProvider;

class DataProviderFactory {

    private DbConnInterface $dbConnection;

    public function __construct(DbConnInterface $dbConnection) {
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
