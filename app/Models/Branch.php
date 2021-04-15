<?php

namespace App\Models;

use App\Traits\TBelongsToUser;
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
    use TBelongsToUser;

    protected $fillable = [
        "user_id",
        "name",
        "location",
        "status",
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Branch $model) {
            $model->status = Branch::getStatusId($model->status ?: 'active')->first();
            if ( !$model->user_id ) {
                $model->user_id = ($user_id = auth()->user()) ? $user_id->id : null;
            }
        });
    }
}
