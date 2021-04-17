<?php

namespace App\Traits;

trait THasByName
{
    /**
     * Scope the model query to certain value and column.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array                          $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->whereIn("name", (array)$value);
    }
}
