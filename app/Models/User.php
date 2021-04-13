<?php

namespace App\Models;

use App\Traits\THasRole;
use App\Traits\THasUsersStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory, Notifiable;
    use HasRoles;
    use THasRole;
    use THasUsersStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Scope the model query to certain mobiles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array                          $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByMobile(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->whereIn('mobile', (array)$value);
    }

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
