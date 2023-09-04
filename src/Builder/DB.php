<?php

namespace Kamandlou\PhpQueryBuilder\Builder;

use Exception;
use Kamandlou\PhpQueryBuilder\Abstracts\Singleton;
use Kamandlou\PhpQueryBuilder\Queries\Select;
use PDO;

class DB extends Singleton
{

    protected string $table = '';
    protected string $prefix = '';
    protected string $separator = '';

    /** If a query errors, this determines how to handle it */
    protected bool $exceptionOnError = false;
    protected int $fetchMode = PDO::FETCH_DEFAULT;

    public function __construct(protected PDO $pdo)
    {
        // if exceptions are already activated in PDO, activate them in Fluent as well
        if ($this->pdo->getAttribute(PDO::ATTR_ERRMODE) === PDO::ERRMODE_EXCEPTION) {
            $this->throwExceptionOnError(true);
        }
    }

    public static function connection(PDO $pdo): static
    {
        return self::getInstance($pdo);
    }

    public function table(string $table): DB
    {
        $this->setTableName($table);
        return $this;
    }

    protected function setTableName(string $table): void
    {
        $this->table = $table;
    }

    public function setPrefix(string $prefix): DB
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function setSeparator(string $separator): DB
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function getFullTableName(): string
    {
        if ($this->table === '') {
            throw new Exception('Table name cannot be empty.');
        }
        return $this->prefix . $this->separator . $this->table;
    }

    public function throwExceptionOnError(bool $flag): DB
    {
        $this->exceptionOnError = $flag;
        return $this;
    }

    public function setFetchMode(int $mode): DB
    {
        $this->fetchMode = $mode;
        return $this;
    }

    public function select(string ...$columns): Select
    {
        return new Select($this, $columns);
    }
}