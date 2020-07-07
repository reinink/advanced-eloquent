# Advanced Eloquent

A set of advanced Eloquent macros for Laravel.

> ⚠️ Note, I've brought much of the functionality provided by this package to Laravel core, in particular the subquery functionality, which has pretty much made this package obsolete. If you want to learn more about these features, be sure to see my [Eloquent Peformance Patterns](https://eloquent-course.reinink.ca/) course, which covers these techniques and others in detail.

## Installation

You can install this package via Composer:

```
composer require reinink/advanced-eloquent
```

This package uses auto-discovery, so there is no further configuration required.

## API

### `addSubSelect($column,  $query)`

- `$column` must be a string.
- `$query` must either be an instance of `Illuminate\Database\Query\Builder` or `Illuminate\Database\Eloquent\Builder`.

### `orderBySub($query, $direction = 'asc', $nullPosition = null)`

- `$query` must either be an instance of `Illuminate\Database\Query\Builder` or `Illuminate\Database\Eloquent\Builder`.
- `$direction` must either be `'asc'` or `'desc'`.
- `$nullPosition` must either be `null`, `'first'` or `'last'`.

### `orderBySubAsc($query, $nullPosition = null)`

- `$query` must either be an instance of `Illuminate\Database\Query\Builder` or `Illuminate\Database\Eloquent\Builder`.
- `$nullPosition` must either be `null`, `'first'` or `'last'`.

### `orderBySubDesc($query, $nullPosition = null)`

- `$query` must either be an instance of `Illuminate\Database\Query\Builder` or `Illuminate\Database\Eloquent\Builder`.
- `$nullPosition` must either be `null`, `'first'` or `'last'`.

*Note: Null positions (`NULLS FIRST` and `NULLS LAST`) are not supported by all databases (ie. MySQL and SQLite), but are supported by PostgreSQL and others.*

## Examples

Get a user's last login date using a subquery:

```php
$users = User::addSubSelect('last_login_at', Login::select('created_at')
    ->whereColumn('user_id', 'users.id')
    ->latest()
)->get();
```

Same example as above, except using the query builder instead:

```php
$users = DB::table('users')->addSubSelect('last_login_at', DB::table('logins')
    ->select('created_at')
    ->whereColumn('user_id', 'users.id')
    ->latest()
)->get()
```

Order users by their company name using a subquery:

```php
$users = User::orderBySub(Company::select('name')->whereColumn('company_id', 'companies.id'))->get();
```

Order users by their last login date, with null values last:

```php
$users = User::addSubSelect('last_login_at', Login::select('created_at')
        ->whereColumn('user_id', 'users.id')
        ->latest()
    )->orderBySubDesc(Login::select('created_at')
        ->whereColumn('user_id', 'users.id')
        ->latest(), 'last'
    )->get();
```
