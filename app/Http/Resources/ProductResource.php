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
//            "branch" => $model->branch_name,
            "name" => $model->name,
            "description" => $model->description,
            "price" => $model->price,
            "image" => $model->image_url ?: "",
//            "qty" => $model->qty,
            "need_prescription" => $model->need_prescription ? 1 : 0,
            "status" => $model->status_text,
            $this->mergeWhen($request->has('branch_id'), fn()=>['qty' => $model->getQtyForBranch($request->get('branch_id'))]),
//            $this->mergeWhen($model->pivot, fn()=>['qty' => $model->pivot->qty]),
        ];
    }
}
