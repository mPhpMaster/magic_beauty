<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductResource
 *
 * @package App\Http\Resources
 */
class ProductResource extends JsonResource
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
            "qty" => $model->qty,
            $this->mergeWhen($model->pivot, fn()=>['qty' => $model->pivot->qty]),
        ];
    }
}
