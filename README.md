# PHP Query Builder
PHP SQL query builder using PDO. It's a quick and light library.

## Features
- Easy interface for creating robust queries
- Supports any database compatible with PDO
- The ability to build complex SELECT, INSERT, UPDATE & DELETE queries with little code
- Type hinting for magic methods with code completion in smart IDEs

## Installation
### Composer
The preferred way to install PHP Query Builder is via [composer](http://getcomposer.org/).

Run this command for installing the PHP Query Builder:
```bash
composer require kamandlou/php-query-builder
```
## Make a connection
```php
$pdo = new PDO('mysql:host=localhost;dbname=test', 'user', 'password');
$db = DB::connection($pdo);
```
### Set fetch mode
```php
$db = DB::connection($pdo)->setFetchMode(PDO::FETCH_ASSOC);
```

## DB Class Methods
`setPrefix(string $prefix)`

Set a prefix to be added to all table names.

```php
$db->setPrefix('db');
```

`setSeparator(string $separator)`

Set the separator character used when building queries.

```php
$db->setPrefix('db')->setSeparator('_'); // Result: db_tableName
```


## CRUD Query Examples

##### SELECT

```php
$db->table('users')
    ->select()
    ->where('id', '>', 100)
    ->orderByDesc('id')
    ->get();
```

```php
$db->table('users')
    ->select('id', 'username')
    ->where('id', '>', 100)
    ->orderByDesc('id')
    ->get();
```
You can get the pure SQL query with `toSql` method.
```php
$db->table('users')
    ->select('id', 'username')
    ->where('id', '>', 100)
    ->orderByRand()
    ->toSql();
```

## Select Class Methods
The Select class provides methods for building SQL SELECT queries.

`select(...$columns)`

Specify columns to select.

```php
$db->select('id', 'name')
// OR
$db->select() // If you don't specify any columns it selects all columns
```

`where($column, $operator, $value)`

Add a WHERE condition to the query.

```php
$db->select()->where('age', '>', 18);
```

- `$column` - The column name to compare against.
- `$operator` - Comparison operator (>, <, =, <>, etc).
- `$value` - The value to compare the column against.

`orWhere($column, $operator, $value)`

Add an OR WHERE condition to the query.
```php
$db->select()->orWhere('age', '>', 18);
```
- `$column` - The column name to compare against.
- `$operator` - Comparison operator (>, <, =, <>, etc).
- `$value` - The value to compare the column against.

`toSql()`

Get the generated SQL query string.
```php
$sql = $db->select()->orWhere('age', '>', 18)->toSql();
echo $sql;
```

`first()`

Execute the query and return the first result row.
```php
$user = $db->table('users')->select('id', 'name')->where('id', '=', 5)->first();
```

`get()`

Execute the query and return all result rows.
```php
$users = $db->table('users')->select('id', 'name')->where('id', '>', 100)->get();
```

`orderBy(string $column, string $direction = 'ASC')`

Set the ORDER BY clause for the query.
```php
$users = $db->table('users')->select('id', 'name', 'age')->orderBy('age', 'DESC')->get();
```
- `$column` - Name of column to sort by.
- `$direction` - Sort direction, 'ASC' or 'DESC'.

`orderByDesc(string $column)`

Set the ORDER BY clause to sort by a column in descending order.
```php
$users = $db->table('users')->select('id', 'name', 'age')->orderByDesc('age')->get();
```
- `$column` - Name of column to sort by.

`orderByRand()`

Set the ORDER BY to random order.
```php
$users = $db->table('users')->select()->orderByRand()->get();
```

`count()`

Get the count of the records.
```php
$count = $db->table('users')->select()
    ->where('id','>',3)
    ->where('id', '<', 100000)
    ->count();
// OR
$count = $db->table('users')->select()->count();
```
