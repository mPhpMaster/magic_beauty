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
            "description" => $model->description,
            "image" => $model->image_url ?: "",
            "status" => $model->status_text,
        ];
    }
}
