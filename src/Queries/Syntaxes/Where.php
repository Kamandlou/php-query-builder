<?php

namespace Kamandlou\PhpQueryBuilder\Queries\Syntaxes;

class Where
{
    public const OR_CONJUNCTION = 'OR';
    public const AND_CONJUNCTION = 'AND';

    public function __construct(
        protected string $column,
        protected string $operator,
        public mixed     $value,
        protected string $conjunction = self::AND_CONJUNCTION
    )
    {
    }

    public function __toString()
    {
        return "$this->conjunction $this->column $this->operator ?";
    }
}