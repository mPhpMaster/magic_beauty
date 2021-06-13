<?php

namespace App\Traits;

/**
 * Trait TImageAttribute
 *
 * @package App\Traits
 */
trait TImageAttribute
{
    /**
     * @return mixed
     */
    public function getImageAttribute()
    {
        return $this->getFirstMedia();
    }

    /**
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return ($image = $this->image) ? $image->getFullUrl() : "";
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