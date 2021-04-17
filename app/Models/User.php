<?php

namespace App\Models;

use App\Interfaces\IRoleConst;
use App\Traits\THasByName;
use App\Traits\THasRole;
use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /**
     * Status type
     */
    public const STATUS_TYPE = 'users';

    use HasApiTokens;
    use HasFactory, Notifiable;
    use HasRoles;
    use THasRole;
    use THasStatus;
    use THasScopeBy;
    use THasByName;

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
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'created_by',
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

    protected static function boot()
    {
        parent::boot();

        static::saving(function (User $user) {
            $user->mobile = parseMobile($user->mobile);
            $user->status = static::getStatusId($user->status ?: 'active')->first();
            if ( $user->isPatient() && !$user->created_by ) {
                $user->created_by = ($creator = auth()->user()) ? $creator->id : null;
            }
        });
    }

    public function isPatient(User $user = null): bool
    {
        $user ??= $this;
        if ( !method_exists($user, 'hasRole') ) {
            return false;
        }

        return $user->hasRole(IRoleConst::PATIENT_ROLE);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

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
        return $query->whereIn('mobile', collect((array)$value)->map(fn($v) => parseMobile($v))->toArray());
    }

    /**
     * Scope the model query to certain mobiles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array                          $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByNameOrMobile(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->whereIn('mobile', collect((array)$value)->map(fn($v) => parseMobile($v))->toArray())
            ->orWhereIn('name', (array)$value);
    }

    /**
     * Scope the model query to doctors only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyDoctors(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->role(IRoleConst::DOCTOR_ROLE);
    }

    /**
     * Scope the model query to Pharmacists only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyPharmacists(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->role(IRoleConst::PHARMACIST_ROLE);
    }

    /**
     * Scope the model query to Administrators only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyAdministrators(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->role(IRoleConst::ADMINISTRATOR_ROLE);
    }

    /**
     * Scope the model query to Supports only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlySupports(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->role(IRoleConst::SUPPORT_ROLE);
    }

    /**
     * Scope the model query to Patients only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyPatients(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->role(IRoleConst::PATIENT_ROLE);
    }

    public function isDoctor(User $user = null): bool
    {
        $user ??= $this;
        if ( !method_exists($user, 'hasRole') ) {
            return false;
        }

        return $user->hasRole(IRoleConst::DOCTOR_ROLE);
    }

    public function isPharmacist(User $user = null): bool
    {
        $user ??= $this;
        if ( !method_exists($user, 'hasRole') ) {
            return false;
        }

        return $user->hasRole(IRoleConst::PHARMACIST_ROLE);
    }

    public function isAdministrator(User $user = null): bool
    {
        $user ??= $this;
        if ( !method_exists($user, 'hasRole') ) {
            return false;
        }

        return $user->hasRole(IRoleConst::ADMINISTRATOR_ROLE);
    }

    public function isSupport(User $user = null): bool
    {
        $user ??= $this;
        if ( !method_exists($user, 'hasRole') ) {
            return false;
        }

        return $user->hasRole(IRoleConst::SUPPORT_ROLE);
    }

    /**
     * @param int|\App\Models\User $user
     *
     * @return $this
     */
    public function assignCreator($user)
    {
        $_user = is_numeric($user) ? User::find($user) : $user;
//        $_user = $_user ?: User::byMobile($user)->first();
        $_user = $_user instanceof User ? $_user->id : null;
        if ( $_user ) {
            $this->created_by = $_user;
            $this->save();
        }

        return $this;
    }
}
