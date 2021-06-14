<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderProductResource
 *
 * @package App\Http\Resources
 */
class OrderProductResource extends JsonResource
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
            "name" => $model->name,
            "price" => $model->pivot->price,
            "qty" => $model->pivot->qty,

            "sub_total" => $model->pivot->sub_total,
            "total" => $model->pivot->total,
            "note" => $model->pivot->note,
        ];
    }
}
