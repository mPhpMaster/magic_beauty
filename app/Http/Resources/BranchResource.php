<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BranchResource
 *
 * @package App\Http\Resources
 */
class BranchResource extends JsonResource
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
        /** @var \App\Models\Branch $model */
        $model = $this->resource;
        if( !$model ) {
            return [];
        }

        return [
            "id" => $model->id,
            "name" => $model->name,
            "name_en" => $model->name_en,
            "name_ar" => $model->name_ar,
            "location" => $model->location,
            "user_id" => (int)$model->user_id,
            "user" => $model->user_name,
            "status" => $model->status_text,
        ];
    }
}
