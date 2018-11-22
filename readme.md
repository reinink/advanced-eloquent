# Advanced Eloquent

A set of advanced Eloquent macros for Laravel.

## Installation

You can install this package via Composer:

```
composer require reinink/advanced-eloquent
```

This package uses auto-discovery, so there is no further configuration required.

## API

This package currently provides three `Eloquent\Builder` macros for working with subqueries in Laravel.

- `addSubSelect($column, $query)`
- `orderBySub($query, $direction = 'asc', $bindings = [])`
- `orderBySubDesc($query, $bindings = [])`

## Examples

Get a user's last login date using a subquery:

```php
$users = User::addSubSelect('last_login_at', Login::select('created_at')
    ->whereColumn('user_id', 'users.id')
    ->latest()
)->get();
```

Order users by their company name using a subquery:

```php
$users = User::orderBySub(Company::select('name')->whereColumn('company_id', 'companies.id'))->get();
```
