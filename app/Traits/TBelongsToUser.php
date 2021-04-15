<?php

namespace App\Traits;

use App\Models\User;

trait TBelongsToUser
{
    /**
     * Scope the model query to certain user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User|int                          $user_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser(\Illuminate\Database\Eloquent\Builder $query, $user_id)
    {
        return $query->whereIn('user_id', (array)$user_id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param int|\App\Models\User $user
     *
     * @return $this
     */
    public function assignUser($user)
    {
        $_user = is_numeric($user) ? User::find($user) : $user;
        $_user = $_user instanceof User ? $_user->id : null;
        if ( $_user ) {
            $this->user_id = $_user;
            $this->save();
        }

        return $this;
    }
}
