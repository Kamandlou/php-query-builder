<?php

namespace Kamandlou\PhpQueryBuilder\Abstracts;

use PDO;

abstract class Singleton
{
    protected static self|null $instance = null;

    protected static function getInstance(PDO $pdo): static
    {
        if (static::$instance === null) {
            static::$instance = new static($pdo);
        }
        return static::$instance;
    }
}