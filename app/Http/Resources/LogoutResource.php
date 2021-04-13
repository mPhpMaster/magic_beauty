<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class LogoutResource
 *
 * @package App\Http\Resources
 */
class LogoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var int|bool $model */
        $model = $this->resource;

        return [

        ];
    }
}
