<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PrescriptionHistoryProductResource
 *
 * @package App\Http\Resources
 */
class PrescriptionHistoryProductResource extends JsonResource
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
        /** @var \App\Models\Product $model */
        $model = $this->resource;

        return [
            "id" => $model->id,
            "category" => $model->category_name,
            "branch" => $model->branch_name,
            "name" => $model->name,
            "description" => $model->description,
            "price" => $model->price,
            "qty" => $model->pivot->qty,
        ];
    }
}
