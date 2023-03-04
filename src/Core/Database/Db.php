<?php

declare(strict_types=1);

namespace App\Core\Database;

use RedBeanPHP\R;
use RedBeanPHP\OODBBean;
use RedBeanPHP\RedException;
use RedBeanPHP\SimpleModel;

class Db {

    public function load(string $type, int $id, string $snippet = NULL): OODBBean {
        return R::load($type, $id, $snippet);
    }

    public function getAll(string $sql, array $bindings = array()): array {
        return R::getAll($sql, $bindings);
    }

    public function getCell(string $sql, array $bindings = array()): string {
        return R::getCell($sql, $bindings);
    }

    public function getRow(string $sql, array $bindings = array()): array {
        return R::getRow($sql, $bindings);
    }

    public function findOne(string $type, string $sql = NULL, array $bindings = array()): OODBBean|null {
        return R::findOne($type, $sql, $bindings);
    }

    public function findAll(string $type, string $sql = NULL, array $bindings = array()): array {
        return R::findAll($type, $sql, $bindings);
    }

    public function dispense(string|array $typeOrBeanArray, int $num = 1, bool $alwaysReturnArray = FALSE): array|OODBBean {
        return R::dispense($typeOrBeanArray, $num, $alwaysReturnArray);
    }

    /**
     * @throws RedException\SQL
     */
    public function store(OODBBean|SimpleModel $bean, bool $unfreezeIfNeeded = FALSE): int|string {
        return R::store($bean, $unfreezeIfNeeded);
    }

    public function trash(string|OODBBean|SimpleModel $beanOrType, int $id = NULL): void {
        R::trash($beanOrType, $id);
    }

    public function trashAll(array $beans): void {
        R::trashAll($beans);
    }

    public function inspect($type = null): array {
        return R::inspect($type);
    }

    public function batch(string $type, array $ids): array {
        return R::batch($type, $ids);
    }

    public function storeAll(array $beans, bool $unfreezeIfNeeded = FALSE): array {
        return R::storeAll($beans, $unfreezeIfNeeded);
    }

    public function count(string $type, string $addSQL = '', array $bindings = array()): int {
        return R::count($type, $addSQL, $bindings);
    }

    public function exec(string $sql, array $bindings = array()): int {
        return R::exec($sql, $bindings);
    }

    public function genSlots(array $array, string $template = NULL): string {
        return R::genSlots($array, $template);
    }

    public function debug(bool $state = true, int $mode = 0): void {
        R::debug($state, $mode);
    }

    public function fancyDebug(bool $value = true) {
        R::fancyDebug($value);
    }

    public function getLogger() {
        return R::getLogger();
    }

}
