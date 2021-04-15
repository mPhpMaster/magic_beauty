<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PrescriptionResource
 *
 * @package App\Http\Resources
 */
class PrescriptionResource extends JsonResource
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
        /** @var \App\Models\Prescription $model */
        $model = $this->resource;

        return [
            "id" => $model->id,
            "doctor" => $model->doctor_name,
            "pharmacist" => $model->pharmacist_name,
            "patient" => $model->patient_name,
            "notes" => $model->notes,
            "status" => $model->status_text,
            "products" => PrescriptionProductResource::collection($model->products),
        ];
    }
}
