<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderResource
 *
 * @package App\Http\Resources
 */
class OrderResource extends JsonResource
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
        /** @var \App\Models\Order $model */
        $model = $this->resource;

        return [
            "id" => $model->id,
            "date" => $model->created_at->format("Y-m-d h:i a"),
            "pay_type" => $model->pay_type_name,
            "branch" => $model->branch_name,
            "sub_total" => $model->sub_total,
            "total" => $model->total,
            "note" => $model->note,
            "status" => $model->status_text,
            "products" => OrderProductResource::collection($model->products),
        ];
    }
}
