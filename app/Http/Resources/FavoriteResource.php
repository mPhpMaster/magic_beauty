<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class FavoriteResource
 *
 * @package App\Http\Resources
 */
class FavoriteResource extends JsonResource
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
        /** @var \App\Models\Favorite $model */
        $model = $this->resource;

        return [
            "product_id" => $model->product_id,
            "product" => $model->product->name,
        ];
    }
}
