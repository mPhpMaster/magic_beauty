<?php


namespace App\Traits;


trait THasUsersStatus
{

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array                          $value
     * @param string                                $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus(\Illuminate\Database\Eloquent\Builder $query, $value, $type = 'users')
    {
        $status = getStatusId($value, $type);
        return $query->whereIn('status', $status->toArray());
    }

    public function scopeByActive(\Illuminate\Database\Eloquent\Builder $query, string $type = 'users') :\Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('active', $type);
    }

    public function scopeByInactive(\Illuminate\Database\Eloquent\Builder $query, string $type = 'users') :\Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('inactive', $type);
    }

    public function getStatusTextAttribute()
    {
        return (string) getStatusName($this->status)->first();
    }
}
