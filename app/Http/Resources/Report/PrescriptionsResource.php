<?php

namespace App\Http\Resources\Report;

use App\Http\Resources\Report\PrescriptionsProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PrescriptionsResource
 *
 * @package App\Http\Resources
 */
class PrescriptionsResource extends JsonResource
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
            "pharmacist" => $model->pharmacist_name,
            "patient" => $model->patient_name,
            "patient_mobile" => $model->patient_mobile,
            "notes" => $model->notes,
            "status" => $model->status_text,
            "products" => PrescriptionsProductResource::collection($model->products),
        ];
    }
}
