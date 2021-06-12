<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * @package App\Http\Resources
 */
class UserResource extends JsonResource
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
            "role" => $model->role_name,
//            "role" => $model->roles()->first()->name,
            "email" => $model->email,
            "mobile" => $model->mobile,
            "device_token" => $model->device_token,
            "image" => $model->image_url ?: "",
            "status" => $model->status_text,
        ];
    }
}
