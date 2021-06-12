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

        return [
            "id" => $model->id,
            "name" => $model->name,
            "location" => $model->location,
            "status" => $model->status_text,
        ];
    }
}
