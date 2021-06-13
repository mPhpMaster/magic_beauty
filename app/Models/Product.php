<?php

namespace App\Models;

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
//    use TBelongsToBranch;
    use TImageAttribute;

    protected $fillable = [
        "category_id",
//        "branch_id",
        "name",
        "description",
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
        });
        static::deleting(function (Product $model) {
            $model->clearMediaCollection();
        });
        static::created(function (Product $model) {
            $branches = Branch::pluck('id')->map(fn($branch) => ['branch_id' => $branch, 'qty' => 0]);
            $model->branches()->sync($branches->toArray());
        });
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_product')
            ->withPivot(['qty']);
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
}
