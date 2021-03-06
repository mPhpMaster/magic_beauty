<?php

namespace App\Models;

use App\Traits\THasMultiDescription;
use App\Traits\THasMultiName;
use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use App\Traits\TImageAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class Product
 *
 * @package App\Models
 */
class Product extends Model implements HasMedia
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
    use THasMultiDescription;

    /**
     * @var string[]
     */
    protected $fillable = [
        "category_id",
//        "branch_id",
        "name_en",
        "name_ar",
        "description_en",
        "description_ar",
        "price",
//        "qty",
        "need_prescription",
        "status",
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Product $model) {
            $model->status = static::getStatusId($model->status ?: 'active')->first();
            $model->name_ar = $model->name_ar ?: $model->name_en;
            $model->name_en = $model->name_en ?: $model->name_ar;
            $model->description_ar = $model->description_ar ?: $model->description_en;
            $model->description_en = $model->description_en ?: $model->description_ar;
        });
        static::deleting(function (Product $model) {
            $model->clearMediaCollection();
        });
        static::created(function (Product $model) {
            $branches = Branch::pluck('id')->map(fn($branch) => ['branch_id' => $branch, 'qty' => 0]);
            $model->branches()->sync($branches->toArray());
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_product')
            ->withPivot(['qty']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function prescriptions()
    {
        return $this->belongsToMany(Prescription::class, 'product_prescription');
    }

    /**
     * @return mixed|string|null
     */
    public function getCategoryNameAttribute()
    {
        return ($c = $this->category) ? $c->name : "";
    }

    /**
     * @param int|\App\Models\Branch $branch
     * @param float                  $qty
     *
     * @return $this
     */
    public function changeQty($branch, float $qty = 1): Product
    {
        $branch_qty = (double)$this->getQtyForBranch($branch);

        if ( ($branch_qty = (float)$branch_qty - $qty) < 0 ) {
            $branch_qty = 0;
        }

        return $this->updateQty($branch, $branch_qty);
    }

    /**
     * @param int|\App\Models\Branch $branch
     *
     * @return float
     */
    public function getQtyForBranch($branch): float
    {
        $branch_id = $branch instanceof Branch ? $branch->id : $branch;
        if ( $product_branch = $this->branches()->where('branch_id', $branch_id)->first() ) {
            $branch_qty = (double)$product_branch->pivot->qty;
        } else {
            $branch_qty = (double)0;
        }

        return $branch_qty;
    }

    /**
     * @param int|\App\Models\Branch $branch
     * @param float                  $qty
     *
     * @return $this
     */
    public function updateQty($branch, float $qty = 0): Product
    {
        $branch_id = $branch instanceof Branch ? $branch->id : $branch;
        $data = [
            $branch_id => [
                'qty' => $qty,
            ],
        ];
        $this->branches()->syncWithoutDetaching($data);

        return $this->refresh();
    }

    /**
     * Scope the model query to certain mobiles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|array                             $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->whereIn('category_id', (array)$value);
    }

    /**
     * Scope the model query to certain mobiles only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|array                             $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByProduct(\Illuminate\Database\Eloquent\Builder $query, $value)
    {
        return $query->whereIn('id', (array)$value);
    }

}
