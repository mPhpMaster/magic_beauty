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
            "date" => $model->created_at->format("Y-m-d h:i a"),
            "doctor" => $model->doctor_name,
            "doctor_id" => $model->doctor_id,
            "pharmacist_id" => $model->pharmacist_id,
            "pharmacist" => $model->pharmacist_name,
            "patient" => $model->patient_name,
            "patient_mobile" => $model->patient_mobile,
            "notes" => $model->notes,
            "status" => $model->status_text,
            "products" => PrescriptionProductResource::collection($model->products),
        ];
    }
}
