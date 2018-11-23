<?php

namespace AdvancedEloquent;

use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        Builder::macro('addSubSelect', function ($column, $query) {
            if (is_null($this->columns)) {
                $this->select($this->from.'.*');
            }

            return $this->selectSub($query->limit(1), $column);
        });

        Builder::macro('orderBySub', function ($query, $direction = 'asc', $nullDirection = null) {
            if (!in_array($direction, ['asc', 'desc'])) {
                throw new Exception('Not a valid direction.');
            }

            if (!in_array($nullDirection, [null, 'first', 'last'], true)) {
                throw new Exception('Not a valid null direction.');
            }

            return $this->orderByRaw(
                implode('', ['(', $query->limit(1)->toSql(), ') ', $direction, $nullDirection ? ' NULLS '.strtoupper($nullDirection) : null]),
                $query->getBindings()
            );
        });

        Builder::macro('orderBySubAsc', function ($query, $nullDirection = null) {
            return $this->orderBySub($query, 'asc', $nullDirection);
        });

        Builder::macro('orderBySubDesc', function ($query, $nullDirection = null) {
            return $this->orderBySub($query, 'desc', $nullDirection);
        });
    }
}
