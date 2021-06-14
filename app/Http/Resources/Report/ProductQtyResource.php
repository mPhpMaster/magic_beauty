<?php

namespace App\Http\Resources\Report;

use App\Models\Branch;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductQtyResource
 *
 * @package App\Http\Resources
 */
class ProductQtyResource extends JsonResource
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
//dd(
//    $model->pivot->sum('qty'),
//    $model->pivot->toArray(),
//);
        return [
            "id" => $model->id,
            "branch" => Branch::getName($model->pivot->branch_id),
            "name" => $model->name,
            "qty" => $model->pivot->qty,
        ];
    }
}
