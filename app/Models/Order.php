<?php

namespace App\Models;

use App\Traits\TBelongsToBranch;
use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 *
 * @package App\Models
 */
class Order extends Model
{
    /**
     * Status type
     */
    public const STATUS_TYPE = 'orders';

    use HasFactory,
        THasStatus,
        THasScopeBy,
        TBelongsToBranch;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'pay_type_id',
        'branch_id',
//        'vat',
//        'vat_percentage',
        'sub_total',
        'total',
        'note',
        'status',
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function (Order $model) {
            $model->status = static::getStatusId($model->status ?: 'pending')->first();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pay_type()
    {
        return $this->belongsTo(PayType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_order')
            ->withPivot([
                'qty',
                'price',
//                'vat',
//                'vat_percentage',
                'sub_total',
                'total',
                'note',
            ]);
    }

    /**
     * Sync the intermediate tables with a list of IDs or collection of models.
     *
     * @param \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Model|array $ids
     * @param bool                                                                     $detaching
     *
     * @return $this
     */
    public function assignProducts($ids, $detaching = true)
    {
        // fix: duplicate when update products
        $this->products()->sync([]);
        $this->products()->sync($ids, $detaching);
        return $this;
    }

    public function scopeBySuccess(\Illuminate\Database\Eloquent\Builder $query, ?string $type = null): \Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('success', $type);
    }

    public function scopeByFailed(\Illuminate\Database\Eloquent\Builder $query, ?string $type = null): \Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('failed', $type);
    }

    public function scopeByPending(\Illuminate\Database\Eloquent\Builder $query, ?string $type = null): \Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('pending', $type);
    }

    public function scopeByFinished(\Illuminate\Database\Eloquent\Builder $query, ?string $type = null): \Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('finished', $type);
    }

    public function scopeByCanceled(\Illuminate\Database\Eloquent\Builder $query, ?string $type = null): \Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('canceled', $type);
    }

    public function setAsSuccess(): bool
    {
        return $this->setStatus('success')->save();
    }

    public function setAsFailed(): bool
    {
        return $this->setStatus('failed')->save();
    }

    public function setAsPending(): bool
    {
        return $this->setStatus('pending')->save();
    }

    public function setAsFinished(): bool
    {
        return $this->setStatus('finished')->save();
    }

    public function setAsCanceled(): bool
    {
        return $this->setStatus('canceled')->save();
    }

    public function getPayTypeNameAttribute(): string
    {
        return ($c = $this->pay_type) ? $c->name : "";
    }
}
