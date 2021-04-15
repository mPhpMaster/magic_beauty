<?php

namespace App\Traits;

trait THasStatus
{
    public static function getStatusId($status = '*', $type = null)
    {
        if ( is_null($type) ) {
            $type = defined('static::STATUS_TYPE') ? static::STATUS_TYPE : 'global';
        }

        return getStatusId($status, $type);
    }

    public static function getStatusName($status = '*', $type = null)
    {
        if ( is_null($type) ) {
            $type = defined('static::STATUS_TYPE') ? static::STATUS_TYPE : 'global';
        }
        return getStatusName($status, $type);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array                          $value
     * @param string|null                           $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus(\Illuminate\Database\Eloquent\Builder $query, $value, $type = null)
    {
        $status = static::getStatusId($value, $type);
        return $query->whereIn('status', $status->toArray());
    }

    public function scopeByActive(\Illuminate\Database\Eloquent\Builder $query, ?string $type = null): \Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('active', $type);
    }

    public function scopeByInactive(\Illuminate\Database\Eloquent\Builder $query, ?string $type = null): \Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('inactive', $type);
    }

    public function getStatusTextAttribute(?string $type = null)
    {
        return (string)static::getStatusName($this->status, $type)->first();
    }

    public function setStatus(string $status, ?string $type = null): self
    {
        $this->status = static::getStatusId($status, $type)->first();

        return $this;
    }
}
