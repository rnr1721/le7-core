<?php

namespace App\Core\User;

use App\Core\Database\Db;
use \RedBeanPHP\OODBBean;

class UserFind {

    private $userFields = ['username'];

    /**
     * Add fields as array or comma-separated string
     * @param string|array $userFields
     * @return self
     */
    public function setSearchField(string|array $userFields): self {
        if (is_array($userFields)) {
            foreach ($userFields as $userField) {
                if (!in_array($userField, $this->userFields)) {
                    $this->userFields[] = $userField;
                }
            }
        }
        if (is_string($userFields)) {
            $fields = explode(',', $userFields);
            foreach ($fields as $field) {
                if (!in_array($field, $this->userFields)) {
                    $this->userFields[] = $field;
                }
            }
        }
        return $this;
    }
    
    public function getUserByFields(Db $db,string $value): OODBBean|null {
        $conds = [];
        $sql = '';
        foreach ($this->userFields as $field) {
            $sql .= ' ' . $field . ' = ? OR ';
            $conds[] = $value;
        }
        $query = rtrim($sql, 'OR ');
        $user = $db->findOne('user', $query, $conds);
        return $user;
    }
    
    public function getUserById(Db $db,string|int $userId):OODBBean|null {
        return $db->findOne('user', ' id = ? ', $userId);
    }
    
}
