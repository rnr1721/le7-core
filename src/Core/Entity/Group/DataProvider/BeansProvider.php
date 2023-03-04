<?php

declare(strict_types=1);

namespace App\Core\Entity\Group\DataProvider;

use RedBeanPHP\R;
use App\Core\Database\DbConnInterface;
use App\Core\Entity\Group\EntityGroupFactory;
use App\Core\Entity\Group\EntityGroupInterface;
use App\Core\Entity\Rules\RulesInterface;
use App\Core\Entity\Group\GroupDataProviderInterface;

class BeansProvider implements GroupDataProviderInterface {

    private array $result = array();
    private EntityGroupFactory $entityFactory;
    private RulesInterface $rules;

    public function __construct(DbConnInterface $dbConnection, RulesInterface $rules, EntityGroupFactory $entityFactory) {
        $dbConnection->connect();
        $this->rules = $rules;
        $this->entityFactory = $entityFactory;
    }

    public function findAll(string $type, string $sql, array $bindings = array()) {
        $this->result = R::findAll($type, $sql, $bindings) ?? [];
    }

    public function getAll(string $sql, array $bindings = []) {
        $this->result = R::getAll($sql, $bindings) ?? [];
    }

    public function getEntity(): EntityGroupInterface {
        return $this->entityFactory->getEntityGroup($this->result, $this->rules, [], []);
    }

}
