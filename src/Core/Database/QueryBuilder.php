<?php

namespace App\Core\Database;

class QueryBuilder {
    
    private array $query;

    public function setQuery(string $query) : self {
        $this->query = $query;
    }
    
    public function where($field):self {
        return $this;
    }
    
    public function and() {
        
    }

    public function order():self {
        return $this;
    }
    
    public function limit():self {
        return $this;
    }
    
    public function build():self {
        return $this;
    }
    
}
