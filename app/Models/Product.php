<?php

namespace App\Models;

use App\Traits\TBelongsToBranch;
use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Status type
     */
    public const STATUS_TYPE = 'global';

    use HasFactory;
    use THasStatus, THasScopeBy;
    use TBelongsToBranch;

    protected $fillable = [
        "category_id",
        "branch_id",
        "name",
        "description",
        "price",
        "qty",
        "status",
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Product $model) {
            $model->status = static::getStatusId($model->status ?: 'active')->first();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function prescriptions()
    {
        return $this->belongsToMany(Prescription::class, 'product_prescription');
    }

    public function getCategoryNameAttribute()
    {
        return ($c = $this->category) ? $c->name : "";
    }
}
