<?php

namespace App\Models;

use App\Traits\TBelongsToBranch;
use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
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

    public function getImageAttribute()
    {
        return $this->getFirstMedia();
    }

    public function getImageUrlAttribute()
    {
        return ($image = $this->image) ? $image->getFullUrl() : "";
    }

    public function getCategoryNameAttribute()
    {
        return ($c = $this->category) ? $c->name : "";
    }

    /**
     * Add a file to the media library.
     *
     * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return $this
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function addImage($file, $preserving_original = false)
    {
        $media = $this->addMedia($file);
        if ( $preserving_original ) {
            $media = $media->preservingOriginal();
        }
        $media->toMediaCollection();

        return $this;
    }
}
