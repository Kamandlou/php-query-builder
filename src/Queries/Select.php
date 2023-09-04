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
    protected string $orderBy = '';

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

    protected function prepare(): void
    {
        if (count($this->wheres)) {
            $this->sql .= " WHERE";
        }
        foreach ($this->wheres as $index => $where) {
            if ($index) {
                $this->sql .= " $where->conjunction $where";
            } else {
                $this->sql .= " $where";
            }
            $this->bindValues[] = $where->value;
        }
        if ($this->orderBy) {
            $this->sql .= $this->orderBy;
        }
    }

    public function toSql(): string
    {
        $this->prepare();
        return $this->sql;
    }

    public function first(): mixed
    {
        $stmt = $this->db->pdo->prepare($this->toSql());
        $stmt->execute($this->bindValues);
        return $stmt->fetch($this->db->fetchMode);
    }

    public function get(): array|bool
    {
        $stmt = $this->db->pdo->prepare($this->toSql());
        $stmt->execute($this->bindValues);
        return $stmt->fetchAll($this->db->fetchMode);
    }

    public function orderBy(string $column, string $direction = 'ASC'): Select
    {
        $this->orderBy = " ORDER BY $column $direction";
        return $this;
    }

    public function orderByDesc(string $column): Select
    {
        return $this->orderBy($column, 'DESC');
    }

    public function orderByRand(): Select
    {
        $this->orderBy = " ORDER BY RAND()";
        return $this;
    }

    public function count(): int
    {
        return 0;
    }
}