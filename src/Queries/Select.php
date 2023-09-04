<?php

namespace Kamandlou\PhpQueryBuilder\Queries;

use Countable;
use Kamandlou\PhpQueryBuilder\Builder\DB;
use Kamandlou\PhpQueryBuilder\Queries\Syntaxes\Where;
use Exception;

class Select implements Countable
{
    protected array $columns;
    protected array $wheres = [];
    protected array $bindValues = [];
    protected string $sql;

    /**
     * @throws Exception
     */
    public function __construct(protected DB $db, array $columns)
    {
        if (count($columns) === 0) {
            $columns = ['*'];
        }
        $this->columns = $columns;
        $this->sql = 'SELECT ' . implode(',', $columns) . ' FROM ' . $this->db->getFullTableName();
    }

    public function where($column, $operator, $value): Select
    {
        $this->wheres[] = new Where($column, $operator, $value);
        return $this;
    }

    public function orWhere($column, $operator, $value): Select
    {
        $this->wheres[] = new Where($column, $operator, $value, Where::OR_CONJUNCTION);
        return $this;
    }

    public function prepare(): Select
    {
        if (count($this->wheres)) {
            $this->sql .= " WHERE";
        }
        foreach ($this->wheres as $where) {
            $this->sql .= " {$where}";
            $this->bindValues[] = $where->value;
        }
        return $this;
    }

    public function toSql(): string
    {
        return $this->sql;
    }

    public function count(): int
    {
        return 0;
    }
}