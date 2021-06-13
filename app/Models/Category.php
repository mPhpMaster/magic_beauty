<?php

namespace App\Models;

use App\Traits\TBelongsToBranch;
use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use App\Traits\TImageAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Category
 *
 * @package App\Models
 */
class Category extends Model implements HasMedia
{
    /**
     * Status type
     */
    public const STATUS_TYPE = 'global';

    use InteractsWithMedia;
    use HasFactory;
    use THasStatus, THasScopeBy;
//    use TBelongsToBranch;
    use TImageAttribute;

    protected $fillable = [
        "category_id",
//        "branch_id",
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
        static::deleting(function (Category $model) {
            $model->clearMediaCollection();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope the model query to certain mobiles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|array                          $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->whereIn('category_id', (array) $value);
    }

}
