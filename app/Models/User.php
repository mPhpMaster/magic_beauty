<?php

namespace App\Models;

use App\Interfaces\IRoleConst;
use App\Traits\THasByName;
use App\Traits\THasMultiName;
use App\Traits\THasRole;
use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use App\Traits\TImageAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    /**
     * Status type
     */
    public const STATUS_TYPE = 'users';

    use InteractsWithMedia;
    use HasApiTokens;
    use HasFactory, Notifiable;
    use HasRoles;
    use THasRole;
    use THasStatus;
    use THasScopeBy;
    use TImageAttribute;
    use THasMultiName;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_en',
        'name_ar',
        'email',
        'mobile',
        'password',
        'status',
        'location',
        'created_by',
        'device_token',
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

        static::saving(function (User $model) {
            $model->mobile = parseMobile($model->mobile);
            $model->status = static::getStatusId($model->status ?: 'active')->first();
            if ( $model->isPatient() && !$model->created_by ) {
                $model->created_by = ($creator = auth()->user()) ? $creator->id : null;
            }
            $model->name_ar = $model->name_ar ?: $model->name_en;
            $model->name_en = $model->name_en ?: $model->name_ar;
        });
        static::deleting(function (User $model) {
            Prescription::ByAnyUser($model->id)->delete();
            $model->clearMediaCollection();
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

    public function branch()
    {
        return $this->hasOne(Branch::class);
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
     * @param string                                $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByNameOrMobile(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query
            ->where(function ($q) use ($value) {
                $q->where('name_en', 'like', "%{$value}%");
                $q->orWhere('name_ar', 'like', "%{$value}%");
                $q->orWhere('mobile', 'like', "%{$value}%");
            });
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
