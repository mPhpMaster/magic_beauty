<?php

namespace App\Traits;

/**
 * Trait THasMultiDescription
 *
 * @package App\Traits
 */
trait THasMultiDescription
{
    /**
     * Scope the model query to certain mobiles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDescription(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->where(columnLocalize('description'), 'like', "%{$value}%");
    }

    /**
     * Scope the model query to certain mobiles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDescriptions(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->where('description_ar', 'like', "%{$value}%")
            ->orWhere('description_en', 'like', "%{$value}%");
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->getAttribute(columnLocalize('description'));
    }
}
