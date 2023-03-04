<?php

declare(strict_types=1);

namespace App\Core\Entity\Group\DataProvider;

use RedBeanPHP\R;
use App\Core\Database\DbConnInterface;
use App\Core\Entity\Group\EntityGroupFactory;
use App\Core\Entity\Group\EntityGroupInterface;
use App\Core\Entity\Rules\RulesInterface;
use App\Core\Entity\Group\GroupDataProviderInterface;

class BeansPaginatedProvider implements GroupDataProviderInterface {

    private EntityGroupFactory $entityFactory;
    private RulesInterface $rules;
    private int $offset = 5;
    private string $table;
    private string $fields = '*';
    private string $addSql = '';
    private array $bindings = array();
    private int $limit = 10;
    private int $page = 1;

    public function __construct(DbConnInterface $dbConnection, RulesInterface $rules, EntityGroupFactory $entityFactory) {
        $dbConnection->connect();
        $this->rules = $rules;
        $this->entityFactory = $entityFactory;
        $this->table = $rules->getName();
    }

    private function paginate(): array {

        $query = "SELECT <FIELDS> FROM $this->table $this->addSql";
        $queryForData = str_replace('<FIELDS>', $this->fields, $query) . " LIMIT ?,? ";
        $queryForCount = str_replace('<FIELDS>', 'COUNT(*)', $query);

        $from = (($this->page - 1) * $this->limit);

        $limits = array(
            $from,
            $this->limit
        );

        $cond = array_merge($this->bindings, $limits);

        $countResult = R::getAll($queryForCount, $this->bindings);
        $count = 0;
        if (!empty($countResult[0]['COUNT(*)'])) {
            $count = intval($countResult[0]['COUNT(*)']);
        }

        $notfound = false;
        $result = R::getAll($queryForData, $cond);
        $lastPage = intval(ceil($count / $this->limit));
        if ($this->page > $lastPage) {
            $notfound = true;
        }

        $nPrev = array();
        $prevClosed = $this->page - 1;
        if ($this->offset > $prevClosed) {
            $pc = $prevClosed;
        } else {
            $pc = $this->offset;
        }
        if ($this->page != 1) {
            for ($i = $this->page - $pc; $i <= $this->page - 1; $i++) {
                $nPrev[] = $i;
            }
            if (!in_array(1, $nPrev)) {
                if ($this->page !== $lastPage) {
                    array_unshift($nPrev, 1, 0);
                }
            }
        }

        $nNext = array();
        $nextClosed = $lastPage - $this->page;
        if ($this->offset > $nextClosed) {
            $nc = $nextClosed;
        } else {
            $nc = $this->offset;
        }
        for ($i = $this->page + 1; $i <= $this->page + $nc; $i++) {
            $nNext[] = $i;
        }
        if (!in_array($lastPage, $nNext)) {
            if ($this->page !== $lastPage) {
                $nNext[] = 0;
                $nNext[] = $lastPage;
            }
        }

        return array(
            'result' => $result,
            'pagination' => array(
                'page' => $this->page,
                'per_page' => $this->limit,
                'first_page' => 1,
                'last_page' => $lastPage,
                'from_record' => $from + 1,
                'to_record' => $from + $this->limit,
                'count' => $count,
                'notfound' => $notfound,
                'prev_pages' => $nPrev,
                'next_pages' => $nNext
            )
        );
    }

    /**
     * @param string $table
     * @return PaginationHelper
     */
    public function setTable(string $table): self {
        $this->table = $table;
        return $this;
    }

    /**
     * @param string $addSql
     * @return PaginationHelper
     */
    public function setAddSql(string $addSql): self {
        $this->addSql = $addSql;
        return $this;
    }

    /**
     * @param array $bindings
     * @return PaginationHelper
     */
    public function setBindings(array $bindings): self {
        $this->bindings = $bindings;
        return $this;
    }

    /**
     * @param int $limit
     * @return PaginationHelper
     */
    public function setLimit(int $limit): self {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int $page
     * @return PaginationHelper
     */
    public function setPage(int $page): self {
        $this->page = $page;
        return $this;
    }

    /**
     * @param string $fields
     * @return PaginationHelper
     */
    public function setFields(string $fields): self {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void {
        $this->offset = $offset;
    }

    public function getEntity(): EntityGroupInterface {
        $data = $this->paginate();
        $result = $data['result'];
        $info = [
            'pagination' => $data['pagination']
        ];
        return $this->entityFactory->getEntityGroup($result, $this->rules, $info, []);
    }

}
