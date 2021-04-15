<?php

namespace App\Traits;

trait THasScopeBy
{
    /**
     * Scope the model query to certain value and column.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $column
     * @param string|array                          $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBy(\Illuminate\Database\Eloquent\Builder $query, string $column, $value)
    {
        return $query->whereIn($column, (array)$value);
    }
}
