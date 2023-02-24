<?php

namespace le7\Core\User;

use \RedBeanPHP\OODBBean;
use le7\Core\Database\Database;

class UserFind {

    private Database $db;

    private $userFields = ['username'];

    public function __construct(Database $db) {
        $this->db = $db;
    }

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
    
    public function getUserByFields(string $value): OODBBean|null {
        $conds = [];
        $sql = '';
        foreach ($this->userFields as $field) {
            $sql .= ' ' . $field . ' = ? OR ';
            $conds[] = $value;
        }
        $query = rtrim($sql, 'OR ');
        $user = $this->db->findOne('user', $query, $conds);
        return $user;
    }
    
}
