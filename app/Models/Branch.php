<?php

namespace App\Models;

use App\Traits\TBelongsToUser;
use App\Traits\THasMultiName;
use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    /**
     * Status type
     */
    public const STATUS_TYPE = 'global';

    use HasFactory;
    use THasScopeBy, THasStatus;
    use TBelongsToUser,
        THasMultiName;

    protected $fillable = [
        "user_id",
        "name_en",
        "name_ar",
        "location",
        "status",
    ];

    /**
     * @param string|\App\Models\Branch $branch_id
     *
     * @return string
     */
    public static function getName($branch_id): string
    {
        return ($branch_id instanceof Branch ? $branch_id : Branch::find($branch_id))->name ?: "";
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Branch $model) {
            $model->status = Branch::getStatusId($model->status ?: 'active')->first();
            if ( !$model->user_id ) {
                $model->user_id = ($user_id = auth()->user()) ? $user_id->id : null;
            }
            $model->name_ar = $model->name_ar ?: $model->name_en;
            $model->name_en = $model->name_en ?: $model->name_ar;
        });
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'branch_product')
            ->withPivot([
                'qty'
            ]);
    }

    public function getUserNameAttribute(): string
    {
        return ($c = $this->user) ? $c->name : "";
    }
}
