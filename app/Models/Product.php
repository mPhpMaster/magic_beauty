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

class Product extends Model implements HasMedia
{
    /**
     * Status type
     */
    public const STATUS_TYPE = 'global';

    use InteractsWithMedia;
    use HasFactory;
    use THasStatus, THasScopeBy;
    use TBelongsToBranch;
    use TImageAttribute;

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
        static::deleting(function (Product $model) {
            $model->clearMediaCollection();
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

    public function changeQty(float $qty = 1): Product
    {
        if ( ($this->qty = (float)$this->qty - $qty) < 0 ) {
            $this->qty = 0;
        }
        $this->save();

        return $this;
    }
}
