<?php

namespace App\Models;

use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Status type
     */
    public const STATUS_TYPE = 'global';

    use HasFactory;
    use THasStatus, THasScopeBy;

    protected $fillable = [
        "category_id",
        "branch_id",
        "name",
        "description",
        "status",
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Category $model) {
            $model->status = static::getStatusId($model->status ?: 'active')->first();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
