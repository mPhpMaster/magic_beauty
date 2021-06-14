<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PatientResource
 *
 * @package App\Http\Resources
 */
class PatientResource extends JsonResource
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
        /** @var \App\Models\User $model */
        $model = $this->resource;

        return [
            "id" => $model->id,
            "name" => $model->name,
            "name_en" => $model->name_en,
            "name_ar" => $model->name_ar,
//            "role" => $model->role_name,
//            "role" => $model->roles()->first()->name,
            "email" => $model->email,
            "mobile" => $model->mobile,
            "location" => $model->location ?: "",
            "image" => $model->image_url ?: "",
            "status" => $model->status_text,
        ];
    }
}
