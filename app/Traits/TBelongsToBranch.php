<?php

namespace App\Traits;

use App\Models\Branch;

/**
 * Trait TBelongsToBranch
 *
 * @package App\Traits
 */
trait TBelongsToBranch
{
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getBranchNameAttribute()
    {
        return ($c = $this->branch) ? $c->name : "";
    }

    /**
     * Scope the model query to certain value and column.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array                          $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByBranch(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->whereIn('branch_id', (array)$value);
    }
}
