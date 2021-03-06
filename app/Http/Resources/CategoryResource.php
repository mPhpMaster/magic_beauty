<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CategoryResource
 *
 * @package App\Http\Resources
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Category $model */
        $model = $this->resource;

        return [
            "id" => $model->id,
//            "category" => $model->category_name,
            "name" => $model->name,
            "name_en" => $model->name_en,
            "name_ar" => $model->name_ar,
            "description" => $model->description,
            "description_en" => $model->description_en,
            "description_ar" => $model->description_ar,
            "image" => $model->image_url ?: "",
            "status" => $model->status_text,
        ];
    }
}
