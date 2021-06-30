<?php

namespace App\Models;

use App\Traits\TBelongsToBranch;
use App\Traits\THasMultiName;
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
    use THasMultiName;

    protected $fillable = [
        "category_id",
//        "branch_id",
        "name_en",
        "name_ar",
        "description",
        "status",
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Category $model) {
            $model->status = static::getStatusId($model->status ?: 'active')->first();
            $model->name_ar = $model->name_ar ?: $model->name_en;
            $model->name_en = $model->name_en ?: $model->name_ar;
        });
        static::deleting(function (Category $model) {
            $model->clearMediaCollection();
            $model->categories()->update(['category_id' => 0]);
            $model->products()->update(['category_id' => 0]);
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
