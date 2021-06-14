<?php

namespace App\Models;

use App\Traits\TBelongsToUser;
use App\Traits\THasScopeBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory,
        THasScopeBy,
        TBelongsToUser;

    protected $fillable = [
        'product_id',
        'user_id',
    ];

    /**
     * Scope the model query to certain user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User|int                          $user_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByProduct(\Illuminate\Database\Eloquent\Builder $query, $user_id)
    {
        return $query->whereIn('product_id', (array)$user_id);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
