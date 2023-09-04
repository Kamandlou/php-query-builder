<?php

namespace Kamandlou\PhpQueryBuilder\Queries;

use Countable;
use Kamandlou\PhpQueryBuilder\Builder\DB;

class Select implements Countable
{
    protected array $columns;

    public function __construct(protected DB $db, array $columns)
    {
        if (count($columns) === 0) {
            $columns = ['*'];
        }
        $this->columns = $columns;
    }

    public function count(): int
    {
        return 0;
    }
}