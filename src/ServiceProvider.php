<?php

namespace AdvancedEloquent;

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

        Builder::macro('orderBySub', function ($query, $direction = 'asc', $bindings = []) {
            return $this->orderByRaw("({$query->limit(1)->toSql()}) {$direction}", $bindings);
        });

        Builder::macro('orderBySubDesc', function ($query, $bindings = []) {
            return $this->orderBySub($query, 'desc', $bindings);
        });
    }
}
