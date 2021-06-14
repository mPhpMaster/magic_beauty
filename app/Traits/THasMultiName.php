<?php

namespace App\Traits;

/**
 * Trait THasMultiName
 *
 * @package App\Traits
 */
trait THasMultiName
{
    /**
     * Scope the model query to certain mobiles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByName(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->where(columnLocalize(), 'like', "%{$value}%");
    }

    /**
     * Scope the model query to certain mobiles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByNames(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->where('name_ar', 'like', "%{$value}%")
            ->orWhere('name_en', 'like', "%{$value}%");
    }

    public function getNameAttribute(): ?string
    {
        return $this->getAttribute(columnLocalize());
    }
}
